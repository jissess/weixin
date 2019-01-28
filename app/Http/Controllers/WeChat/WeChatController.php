<?php

namespace App\Http\Controllers\WeChat;

use EasyWeChat\Factory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WeChatController extends Controller
{
    protected $app;

    public function __construct()
    {
        $config = config('wechat.official_account.default');
        date_default_timezone_set('Asia/Shanghai');
        $this->app = Factory::officialAccount($config);

    }

    public function getUser()
    {
        return $this->app->user->list();
    }

    public function server()
    {
        $this->app->server->push(function ($message) {
            //用于查看返回消息
            Log::info($message);
            switch ($message['MsgType']) {
                case 'event':
                    if ($message['Event'] == 'subscribe') {
                        Log::info('_openid_' . $message['FromUserName'] .'_' . '关注公众号');
                        return '欢迎关注吴业家的测试公众号';
                    }

                    if($message['Event'] == 'unsubscribe') {
                        Log::info('_openid_' . $message['FromUserName'] .'_' . '取消关注公众号');
                        return '已经取消关注';
                    }
                    return '关注此公众号，美好生活即将起航！';
                    break;
                case 'text':
                    //返回用户发送的消息
                    return $message['Content'];
                    break;
                case 'image':
                    return '收到图片消息';
                    break;
                case 'voice':
                    return '收到语音消息';
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':
                    return '收到坐标消息';
                    break;
                case 'link':
                    return '收到链接消息';
                    break;
                case 'file':
                    return '收到文件消息';
                // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }
        });



        return $this->app->server->serve();
    }

    public function bindUser(Request $request)
    {
        Log::info('bind __ ');
        return $this->app->oauth->scopes(['snsapi_userinfo'])->setRequest($request)->redirect();
    }

    public function userSet(Request $request)
    {
        $user = $this->app->oauth->user()->getId();
        Log::info($user);

        dd('成功绑定');
    }

    public function sendText()
    {
        $content = 'hello world!';
        $this->app->broadcasting->sendText($content, $this->getUser()['data']['openid']);

        return response_success();
    }

    public function sendTemplateMsg()
    {
        $result = $this->app->template_message->send([
            'touser' => "oSAUb0lOEfQu8Q_up87ZsyAp_GUU",
            'template_id' => "WkyesmEZqKqxvfdglBWmxDyZy4SNweGyd2MfqJS9Pzg",
            'url' => "https://www.nblistener.com/video.php?videourl=",  //上边的域名
            'miniprogram' => [],
            'data' => [
                'first' => ['value' => '发布详情如下', 'color' => '#173177'],
                'keyword1' => ['value' => 'eee', 'color' => '#173177'],
                'keyword2' => ['value' => 'aaa', 'color' => '#173177'],
                'remark' => ['value' => '点击播放视频', 'color' => '#173177'],
            ]
        ]);

        return response_success();
    }

    public function setMenu()
    {
        $buttons = [
            [
                "name" => "上海新闻",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "上海新闻",
                        "url"  => "http://sh.sina.com.cn/"
                    ],
                    [
                        "type" => "view",
                        "name" => "热门推荐",
                        "url"  => "http://news.baidu.com/"
                    ]
                ]
            ],
            [
                "name"       => "福利活动",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "免费读书",
                        "url"  => "http://avg.163.com/home"
                    ],
                    [
                        "type" => "view",
                        "name" => "搜图片",
                        "url"  => "https://www.veer.com/"
                    ]
                ],
            ],
            [
                "name"       => "便民服务",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "日历",
                        "url"  => "https://wannianrili.51240.com/"
                    ],
                    [
                        "type" => "view",
                        "name" => "黄页",
                        "url"  => "http://www.114chn.com/"
                    ]
                ],
            ]
        ];
        $setRes = $this->app->menu->create($buttons);

        return response_success([]);
    }

}
