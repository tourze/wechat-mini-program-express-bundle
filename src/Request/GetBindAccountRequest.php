<?php

namespace WechatMiniProgramExpressBundle\Request;

use WechatMiniProgramBundle\Request\WithAccountRequest;

/**
 * 拉取已绑定账号请求
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/immediate-delivery/deliver-by-business/getBindAccount.html
 */
class GetBindAccountRequest extends WithAccountRequest
{
    public function getRequestPath(): string
    {
        return '/cgi-bin/express/local/business/shop/get';
    }

    public function getRequestOptions(): ?array
    {
        return [];
    }

    public function getRequestMethod(): ?string
    {
        return 'GET';
    }
}
