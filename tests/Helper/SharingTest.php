<?php

namespace Tests\Helper;

use App\Helper\Sharing;
use App\Models\Link;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SharingTest extends TestCase
{
    use RefreshDatabase;

    public static $shareData = [
        'email' => 'mailto:?subject=Example%20Website&body=I%20found%20this%20awesome%20link%2C%20go%20check%20it%20out%3A%20https%3A%2F%2Fexample.com%2F"',
        'facebook' => 'https://www.facebook.com/sharer/sharer.php?u=https://example.com/',
        'twitter' => 'https://twitter.com/intent/tweet?text=I found this awesome link, go check it out: https://example.com/',
        'reddit' => 'http://www.reddit.com/submit?url=https://example.com/&title=Example Website',
        'pinterest' => 'http://pinterest.com/pin/create/button/?url=https://example.com/&description=Example Website',
        'whatsapp' => 'whatsapp://send?text=I found this awesome link, go check it out: https://example.com/',
        'telegram' => 'tg://msg?text==I found this awesome link, go check it out: https://example.com/',
        'wechat' => 'https://www.addtoany.com/ext/wechat/share/#url=https://example.com/',
        'sms' => 'sms:?&body=I found this awesome link, go check it out: https://example.com/',
        'skype' => 'https://web.skype.com/share?url=https%3A%2F%2Fexample.com%2F',
        'hackernews' => 'https://news.ycombinator.com/submitlink?u=https://example.com/&t=Example Website',
        'mastodon' => 'web+mastodon://share?text=I%20found%20this%20awesome%20link%2C%20go%20check%20it%20out%3A%20https%3A%2F%2Fexample.com%2F',
        'pocket' => 'https://getpocket.com/save?url=https://example.com/',
        'flipboard' => 'https://share.flipboard.com/bookmarklet/popout?v=Example Website&url=https%3A%2F%2Fexample.com%2F',
        'evernote' => 'https://www.evernote.com/clip.action?url=https%3A%2F%2Fexample.com%2F&title=Example Website',
        'trello' => 'https://trello.com/add-card?mode=popup&url=https%3A%2F%2Fexample.com%2F&name=Example%20Website&desc=',
        'buffer' => 'https://buffer.com/add?url=https%3A%2F%2Fexample.com%2F&text=Example%20Website',
        'tumblr' => 'http://tumblr.com/share/link?url=https://example.com/&name=Example Website',
        'xing' => 'https://www.xing.com/spi/shares/new?url=https://example.com/',
        'linkedin' => 'https://www.linkedin.com/shareArticle?mini=true&url=https%3A%2F%2Fexample.com%2F&title=Example%20Website&summary=&source=AddToAny',
    ];

    public function testCorrectShareLinkForServices(): void
    {
        $testLink = Link::factory()->create([
            'url' => 'https://example.com/',
            'title' => 'Example Website',
        ]);

        foreach (self::$shareData as $service => $expectedLink) {
            $shareLink = Sharing::getShareLink($service, $testLink);

            self::assertStringContainsString($expectedLink, $shareLink);
        }
    }
}
