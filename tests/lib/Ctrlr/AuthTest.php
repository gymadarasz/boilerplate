<?php declare(strict_types = 1);

/**
 * PHP version 7.4
 *
 * @category  PHP
 * @package   Madsoft\Library\Test\Ctrlr
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */

namespace Madsoft\Library\Test\Ctrlr;

use Madsoft\Library\Folders;
use Madsoft\Library\Inspector;
use Madsoft\Library\Logger;
use Madsoft\Library\RequestTest;
use Madsoft\Library\Session;
use RuntimeException;
use SplFileInfo;

/**
 * AuthTest
 *
 * @category  PHP
 * @package   Madsoft\Library\Test\Ctrlr
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class AuthTest extends RequestTest
{
    const EMAIL = 'tester@testing.com';
    const PASSWORD_FIRST = 'first1234';
    const PASSWORD = 'pass1234';
    const MAILS_FOLDER = __DIR__ . '/../../../mails';

    protected Session $session;
    protected Inspector $inspector;
    protected Folders $folders;
    
    /**
     * Method __construct
     *
     * @param Session   $session   session
     * @param Inspector $inspector inspector
     * @param Folders   $folders   folders
     */
    public function __construct(
        Session $session,
        Inspector $inspector,
        Folders $folders
    ) {
        $this->session = $session;
        $this->inspector = $inspector;
        $this->folders = $folders;
    }


    /**
     * Method testLogin
     *
     * @return void
     */
    public function testLogin(): void
    {
        $this->canSeeLogin();
        $this->canSeeLoginFails();
        $this->canSeeRegistry();
        $this->canSeeRegistryFails();
        $this->canSeeRegistryWorks();
        $this->canSeeActivationMail();
        $this->canSeeActivationFails();
        $this->canSeeActivationWorks();
        $this->canSeeRegistryFailsByUserExists();
        $this->canSeeLoginWorks(self::PASSWORD_FIRST);
        $this->canSeeLogoutWorks();
        $this->canSeeResetPassword();
        $this->canSeeResetPasswordFails();
        $this->canSeeResetPasswordWorks();
        $this->canSeeNewPasswordFails();
        $this->canSeeNewPassword();
        $this->canSeeNewPasswordWorks();
        $this->canSeeLoginWorks();
        $this->canSeeLogoutWorks();
    }
    
    /**
     * Method canSeeLogin
     *
     * @return void
     */
    protected function canSeeLogin(): void
    {
        $contents = $this->get('q=login');
        $this->assertStringContains('Login', $contents);
    }
    
    /**
     * Method canSeeLoginFails
     *
     * @return void
     */
    protected function canSeeLoginFails(): void
    {
        $contents = $this->post(
            'q=login',
            [
                'csrf' => $this->session->get('csrf'),
            //                'email' => self::EMAIL,
            //                'password' => self::PASSWORD,
            ]
        );
        $this->assertStringContains('Login failed', $contents);
        
        $contents = $this->post(
            'q=login',
            [
                'csrf' => $this->session->get('csrf'),
                'email' => self::EMAIL,
            //                'password' => self::PASSWORD,
            ]
        );
        $this->assertStringContains('Login failed', $contents);
        
        $contents = $this->post(
            'q=login',
            [
                'csrf' => $this->session->get('csrf'),
            //                'email' => self::EMAIL,
                'password' => self::PASSWORD_FIRST,
            ]
        );
        $this->assertStringContains('Login failed', $contents);
        
        $contents = $this->post(
            'q=login',
            [
                'csrf' => $this->session->get('csrf'),
                'email' => '',
                'password' => '',
            ]
        );
        $this->assertStringContains('Login failed', $contents);
        
        $contents = $this->post(
            'q=login',
            [
                'csrf' => $this->session->get('csrf'),
                'email' => self::EMAIL,
                'password' => self::PASSWORD_FIRST,
            ]
        );
        $this->assertStringContains('Login failed', $contents);
    }
    
    /**
     * Method canSeeRegistry
     *
     * @return void
     */
    protected function canSeeRegistry(): void
    {
        $contents = $this->get('q=registry');
        $this->assertStringContains('Registry', $contents);
    }
    
    /**
     * Method canSeeRegistryFails
     *
     * @return void
     */
    protected function canSeeRegistryFails(): void
    {
        // TODO: negative tests for invalid registry form, user already exists etc..
        $contents = $this->post(
            'q=registry',
            [
                'csrf' => $this->session->get('csrf'),
            //                'email' => '',
            //                'email_retype' => '',
            //                'password' => '',
            ]
        );
        $this->assertStringContains('Registration failed', $contents);
        $this->assertStringContains('Email field is missing', $contents);
        $this->assertStringContains('Password field is missing', $contents);
        
        $contents = $this->post(
            'q=registry',
            [
                'csrf' => $this->session->get('csrf'),
                'email' => '',
                'email_retype' => '',
                'password' => '',
            ]
        );
        $this->assertStringContains('Registration failed', $contents);
        $this->assertStringContains('Email field is missing', $contents);
        $this->assertStringContains('Password field is missing', $contents);
        
        $contents = $this->post(
            'q=registry',
            [
                'csrf' => $this->session->get('csrf'),
                'email' => 'itisnotvalid',
                'email_retype' => '',
                'password' => '',
            ]
        );
        $this->assertStringContains('Registration failed', $contents);
        $this->assertStringContains('Email field is invalid', $contents);
        $this->assertStringContains('Password field is missing', $contents);
        
        $contents = $this->post(
            'q=registry',
            [
                'csrf' => $this->session->get('csrf'),
                'email' => 'valid@email.com',
                'email_retype' => 'wrong@retype.com',
                'password' => '',
            ]
        );
        $this->assertStringContains('Registration failed', $contents);
        $this->assertStringContains('Email fields are mismatch', $contents);
        $this->assertStringContains('Password field is missing', $contents);
        
        $contents = $this->post(
            'q=registry',
            [
                'csrf' => $this->session->get('csrf'),
                'email' => 'valid@email.com',
                'email_retype' => 'valid@email.com',
                'password' => 'short',
            ]
        );
        $this->assertStringContains('Registration failed', $contents);
        $this->assertStringContains('Password is too short', $contents);
        
        $contents = $this->post(
            'q=registry',
            [
                'csrf' => $this->session->get('csrf'),
                'email' => 'valid@email.com',
                'email_retype' => 'valid@email.com',
                'password' => 'longbutdoesnothavenumber',
            ]
        );
        $this->assertStringContains('Registration failed', $contents);
        $this->assertStringContains(
            'Password does not any contains number',
            $contents
        );
        
        $contents = $this->post(
            'q=registry',
            [
                'csrf' => $this->session->get('csrf'),
                'email' => 'valid@email.com',
                'email_retype' => 'valid@email.com',
                'password' => 'nospecchar123',
            ]
        );
        $this->assertStringContains('Registration failed', $contents);
        $this->assertStringContains(
            'Password does not contains any special character',
            $contents
        );
    }

    /**
     * Method canSeeRegistryWorks
     *
     * @return void
     */
    protected function canSeeRegistryWorks(): void
    {
        $contents = $this->post(
            'q=registry',
            [
                'csrf' => $this->session->get('csrf'),
                'email' => self::EMAIL,
                'email_retype' => self::EMAIL,
                'password' => self::PASSWORD_FIRST,
            ]
        );
        $this->assertStringContains('Registration success', $contents);
        $this->assertStringContains('Activation email', $contents);
    }
    
    /**
     * Method canSeeActivationMail
     *
     * @return void
     */
    protected function canSeeActivationMail(): void
    {
        $emailFilename = $this->getLastEmailFilename();
        $this->assertStringContains(self::EMAIL, $emailFilename);
        $this->assertStringContains('Account activation', $emailFilename);
        
        $emailContents = $this->getLastEmailContents();
        $found = false;
        $activationLink = $this->getActivationLink(self::EMAIL);
        foreach ($this->inspector->outer($emailContents)('a') as $link) {
            if ($link->href === $activationLink) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found);
    }
    
    /**
     * Method canSeeActivationFails
     *
     * @return void
     */
    protected function canSeeActivationFails(): void
    {
        $contents = $this->get('q=activate');
        $this->assertStringContains('Invalid token', $contents);
        
        $contents = $this->get('q=activate&token=wrong-token');
        $this->assertStringContains('Invalid token', $contents);
    }
    
    /**
     * Method canSeeActivationWorks
     *
     * @return void
     */
    protected function canSeeActivationWorks(): void
    {
        $contents = $this->get('q=activate&token=' . $this->getActivationToken());
        $this->assertStringContains('Account activated', $contents);
    }
    
    /**
     * Method canSeeRegistryFailsByUserExists
     *
     * @return void
     */
    protected function canSeeRegistryFailsByUserExists(): void
    {
        $contents = $this->post(
            'q=registry',
            [
                'csrf' => $this->session->get('csrf'),
                'email' => self::EMAIL,
                'email_retype' => self::EMAIL,
                'password' => self::PASSWORD_FIRST,
            ]
        );
        $this->assertStringContains('Registration failed', $contents);
        $this->assertStringContains('User already exists', $contents);
    }
    
    /**
     * Method canSeeLoginWorks
     *
     * @param string $password password
     *
     * @return void
     */
    protected function canSeeLoginWorks(string $password = self::PASSWORD): void
    {
        $contents = $this->post(
            'q=login',
            [
                'csrf' => $this->session->get('csrf'),
                'email' => self::EMAIL,
                'password' => $password,
            ]
        );
        $this->assertStringContains('Login failed', $contents);
    }
    
    /**
     * Method canSeeLogoutWorks
     *
     * @return void
     */
    protected function canSeeLogoutWorks(): void
    {
        $contents = $this->get('q=logout');
        $this->assertStringContains('Logout success', $contents);
    }
    
    /**
     * Method canSeeResetPassword
     *
     * @return void
     */
    protected function canSeeResetPassword(): void
    {
        $contents = $this->get('q=reset');
        $this->assertStringContains('Password reset', $contents);
    }
    
    /**
     * Method canSeeResetPasswordFails
     *
     * @return void
     */
    protected function canSeeResetPasswordFails(): void
    {
        $contents = $this->post(
            'q=reset',
            [
                'csrf' => $this->session->get('csrf'),
            //                'email' => 'nonexist@useremail.com',
            ]
        );
        $this->assertStringContains('Reset password failed', $contents);
        
        $contents = $this->post(
            'q=reset',
            [
                'csrf' => $this->session->get('csrf'),
                'email' => 'nonexist@useremail.com',
            ]
        );
        $this->assertStringContains('Reset password failed', $contents);
    }
    
    /**
     * Method canSeeResetPasswordWorks
     *
     * @return void
     */
    protected function canSeeResetPasswordWorks(): void
    {
        $contents = $this->post(
            'q=reset',
            [
                'csrf' => $this->session->get('csrf'),
                'email' => self::EMAIL,
            ]
        );
        $this->assertStringContains('email sent', $contents);
    }
    
    /**
     * Method canSeeNewPasswordFails
     *
     * @return void
     */
    protected function canSeeNewPasswordFails(): void
    {
        $contents = $this->get('q=reset&token=wron-token');
        $this->assertStringContains('Invalid token', $contents);
        
        $contents = $this->post(
            'q=reset&token=' . $this->getResetToken(),
            [
                'csrf' => $this->session->get('csrf'),
            //                'password' => '',
            //                'password_retype' => '',
            ]
        );
        $this->assertStringContains('Password is missing', $contents);
        
        $contents = $this->post(
            'q=reset&token=' . $this->getResetToken(),
            [
                'csrf' => $this->session->get('csrf'),
                'password' => '',
            //                'password_retype' => '',
            ]
        );
        $this->assertStringContains('Password is missing', $contents);
        
        $contents = $this->post(
            'q=reset&token=' . $this->getResetToken(),
            [
                'csrf' => $this->session->get('csrf'),
                'password' => 'short',
                'password_retype' => '',
            ]
        );
        $this->assertStringContains('Password is too short', $contents);
        
        $contents = $this->post(
            'q=reset&token=' . $this->getResetToken(),
            [
                'csrf' => $this->session->get('csrf'),
                'password' => 'longwithoutnumbers',
                'password_retype' => '',
            ]
        );
        $this->assertStringContains(
            "Password doesn't contain any numbers",
            $contents
        );
        
        $contents = $this->post(
            'q=reset&token=' . $this->getResetToken(),
            [
                'csrf' => $this->session->get('csrf'),
                'password' => 'withoutspecchar1234',
                'password_retype' => '',
            ]
        );
        $this->assertStringContains(
            "Password doesn't contain any special character",
            $contents
        );
    }
    
    /**
     * Method canSeeNewPassword
     *
     * @return void
     */
    protected function canSeeNewPassword(): void
    {
        $contents = $this->get('q=reset&token=' . $this->getResetToken());
        $this->assertStringContains('New password', $contents);
    }
    
    /**
     * Method canSeeNewPasswordWorks
     *
     * @return void
     */
    protected function canSeeNewPasswordWorks(): void
    {
        $contents = $this->post(
            'q=reset&token=' . $this->getResetToken(),
            [
                'csrf' => $this->session->get('csrf'),
                'password' => self::EMAIL,
                'password_retype' => self::PASSWORD,
            ]
        );
        $this->assertStringContains('Password is changed', $contents);
    }
    
    /**
     * Method getLastEmail
     *
     * @param string $folder folder
     *
     * @return SplFileInfo
     * @throws RuntimeException
     */
    protected function getLastEmail(string $folder = self::MAILS_FOLDER): SplFileInfo
    {
        //        $dir = realpath($folder);
        //        if (false === $dir) {
        //            throw new RuntimeException('Folder not exists: ' . $folder);
        //        }
        $mails = $this->folders->getFilesRecursive($folder);
        $latest = null;
        foreach ($mails as $mail) {
            if ($mail->isDir()) {
                continue;
            }
            if (!$latest) {
                $latest = $mail;
                continue;
            }
            if ($latest->getMTime() < $mail->getMTime()) {
                $latest = $mail;
            }
        }
        if (!$latest) {
            throw new RuntimeException(
                'Mail file is not found in folder: ' . $folder
            );
        }
        return $latest;
    }
    
    /**
     * Method getLastEmailFilename
     *
     * @return string
     */
    protected function getLastEmailFilename(): string
    {
        return $this->getLastEmail()->getFilename();
    }
    
    /**
     * Method getLastEmailContents
     *
     * @return string
     * @throws RuntimeException
     */
    protected function getLastEmailContents(): string
    {
        $mailfile = $this->getLastEmailFilename();
        $contents = file_get_contents($mailfile);
        if (false === $contents) {
            throw new RuntimeException('Unable to read: ' . $mailfile);
        }
        return $contents;
    }
    
    /**
     * Method getActivationLink
     *
     * @param string $email email
     *
     * @return string
     */
    protected function getActivationLink(string $email): string
    {
        return $email.''; // TODO ...
    }
    
    /**
     * Method getActivationToken
     *
     * @return string
     */
    protected function getActivationToken(): string
    {
        return ''; // TODO ...
    }
    
    /**
     * Method getResetToken
     *
     * @return string
     */
    protected function getResetToken(): string
    {
        return ''; // TODO ...
    }
}
