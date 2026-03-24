<?php

namespace App\Http\Controllers;

use App\Helper\UpdateHelper;
use App\Models\Link;
use App\Models\LinkList;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Masterminds\HTML5;
use Kovah\HtmlMeta\Exceptions\DisallowedIpException;
use Kovah\HtmlMeta\Exceptions\InvalidUrlException;
use Kovah\HtmlMeta\Exceptions\UnreachableUrlException;
use Kovah\HtmlMeta\Facades\HtmlMeta as HtmlMetaFacade;

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
            ->withTrashed()
            ->visibleForUser()
            ->where('url', trim($query))
            ->where('id', '!=', $request->input('ignore_id', 0))
            ->first();

        return response()->json([
            'linkFound' => $link,
            'linkDeleted' => $link?->trashed(),
            'editLink' => $link ? route('links.edit', ['link' => $link]) : null,
            'restoreLink' => route('trash-restore'),
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
    public function htmlKeywordsFromUrl(Request $request): JsonResponse
    {
        $request->validate([
            'url' => ['url'],
        ]);

        $url = $request->input('url');

        try {
            $response = HtmlMetaFacade::forUrl($url)->getResponse();
        } catch (DisallowedIpException|InvalidUrlException|UnreachableUrlException) {
            return response()->json(['keywords' => null]);
        }

        if (!$response?->successful()) {
            return response()->json(['keywords' => null]);
        }

        return response()->json(['keywords' => $this->extractKeywords($response->body())]);
    }

    /**
     * @return array<int, string>
     */
    protected function extractKeywords(string $html): array
    {
        $html5 = new HTML5();
        $dom = $html5->loadHTML($html);
        $keywords = [];

        /** @var \DOMElement $metaTag */
        foreach ($dom->getElementsByTagName('meta') as $metaTag) {
            if (strtolower($metaTag->getAttribute('name')) !== 'keywords') {
                continue;
            }

            $keywords = explode(',', $metaTag->getAttributeNode('content')?->value);
            $keywords = array_map(fn($keyword) => trim(e($keyword)), $keywords);
            array_push($keywords, ...$keywords);
        }

        return $keywords;
    }
}
