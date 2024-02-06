<?php

namespace App\Http\Controllers;

use App\Helper\UpdateHelper;
use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Masterminds\HTML5;

class FetchController extends Controller
{
    public function getTags(Request $request): JsonResponse
    {
        $query = $request->input('query', false);

        if (!$query) {
            return response()->json([]);
        }

        $tags = Tag::query()
            ->visibleForUser()
            ->where('name', 'like', '%' . escapeSearchQuery($query) . '%')
            ->with('user:id,name')
            ->oldest('name')
            ->get();

        return response()->json($tags);
    }

    public function getLists(Request $request): JsonResponse
    {
        $query = $request->input('query', false);

        if (!$query) {
            return response()->json([]);
        }

        $tags = LinkList::query()
            ->visibleForUser()
            ->where('name', 'like', '%' . escapeSearchQuery($query) . '%')
            ->with('user:id,name')
            ->oldest('name')
            ->get();

        return response()->json($tags);
    }

    /**
     * Returns a boolean flag which indicates that there already is a link
     * present for the given URL.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function searchExistingUrls(Request $request): JsonResponse
    {
        $query = $request->input('query', false);

        if (!$query) {
            return response()->json([]);
        }

        $link = Link::query()
            ->visibleForUser()
            ->where('url', trim($query))
            ->where('id', '!=', $request->input('ignore_id', 0))
            ->first();

        return response()->json([
            'linkFound' => $link !== null,
            'editLink' => $link ? route('links.edit', ['link' => $link]) : null,
        ]);
    }

    public static function checkForUpdates(): JsonResponse
    {
        $updateCheck = UpdateHelper::checkForUpdates(true);

        return response()->json(['checkResult' => $updateCheck]);
    }

    /**
     * Returns the HTML for a given URL to prevent CORS issues in the frontend
     * implementation.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function htmlKeywordsFromUrl(Request $request)
    {
        $request->validate([
            'url' => ['url'],
        ]);

        $url = $request->input('url');
        $newRequest = setupHttpRequest(3);
        $response = $newRequest->get($url);

        if ($response->successful()) {
            $html5 = new HTML5();
            $dom = $html5->loadHTML($response->body());
            $keywords = [];
            /** @var \DOMElement $metaTag */
            foreach ($dom->getElementsByTagName('meta') as $metaTag) {
                if (strtolower($metaTag->getAttribute('name')) === 'keywords') {
                    $keywords = explode(',', $metaTag->getAttributeNode('content')?->value);
                    $keywords = array_map(fn($keyword) => trim(e($keyword)), $keywords);
                    array_push($keywords, ...$keywords);
                }
            }
            return response()->json(['keywords' => $keywords]);
        }

        return response()->json(['keywords' => null]);
    }
}
