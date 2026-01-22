<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eccube\Tests\Web\Admin\Setting\System;

use Eccube\Entity\Member;
use Eccube\Repository\MemberRepository;
use Eccube\Service\TwoFactorAuthService;
use Eccube\Tests\Web\Admin\AbstractAdminWebTestCase;

class TwoFactorAuthControllerTest extends AbstractAdminWebTestCase
{
    /**
     * @var MemberRepository
     */
    protected $memberRepository;

    /**
     * @var TwoFactorAuthService
     */
    protected $twoFactorAuthService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->memberRepository = $this->entityManager->getRepository(Member::class);
        $this->twoFactorAuthService = static::getContainer()->get(TwoFactorAuthService::class);
    }

    /**
     * MFAバイパス脆弱性テスト
     * 2FAキーが設定済みのユーザーが2FA未認証状態で設定画面にアクセスした場合、
     * 認証画面にリダイレクトされることを確認
     */
    public function testSetRedirectsToAuthWhenTwoFactorAuthKeyAlreadyConfigured()
    {
        // 2FAが無効な場合はスキップ
        if (!$this->twoFactorAuthService->isEnabled()) {
            $this->markTestSkipped('2FAが無効のためスキップ');
        }

        // 2FA設定済みの新規メンバーを作成
        $Member = $this->createMember();
        $Member->setTwoFactorAuthEnabled(true);
        $Member->setTwoFactorAuthKey($this->twoFactorAuthService->createSecret());
        $this->entityManager->persist($Member);
        $this->entityManager->flush();

        // 新しいMemberでログインし直す
        $this->loginTo($Member);

        // 2FA未認証状態で設定画面にアクセス
        $this->client->request('GET', $this->generateUrl('admin_two_factor_auth_set'));

        $response = $this->client->getResponse();

        // 認証画面にリダイレクトされることを確認
        $this->assertTrue(
            $response->isRedirect($this->generateUrl('admin_two_factor_auth')),
            '2FAキー設定済みユーザーが未認証で設定画面にアクセスした場合、認証画面にリダイレクトされるべき。実際のレスポンス: Status='.$response->getStatusCode().', Location='.$response->headers->get('Location')
        );
    }
}
