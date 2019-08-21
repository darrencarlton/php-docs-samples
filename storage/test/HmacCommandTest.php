<?php
/**
 * Copyright 2019 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Google\Cloud\Samples\Storage\Tests;

use Google\Cloud\Storage\StorageClient;
use Google\Cloud\TestUtils\TestTrait;
use Symfony\Component\Console\Tester\CommandTester;
use PHPUnit\Framework\TestCase;

/**
 * Unit Tests for BucketPolicyOnlyCommand.
 */
class HmacCommandTest extends TestCase
{
    use TestTrait;

    protected $commandTesterList;
    protected $commandTesterCreate;
    protected $commandTesterManage;
    protected $storage;
    protected $hmacServiceAccount;
    protected $accessId;

    public function setUp()
    {
        $application = require __DIR__ . '/../storage.php';
        $this->commandTesterList = new CommandTester($application->get('hmac-sa-list'));
        $this->commandTesterCreate = new CommandTester($application->get('hmac-sa-create'));
        $this->commandTesterManage = new CommandTester($application->get('hmac-sa-manage'));
        $this->storage = new StorageClient();
        $this->hmacServiceAccount = $this->requireEnv('STORAGE_HMAC_KEY_SERVICE_ACCOUNT');
        // Delete all HMAC keys.
        deleteAllHmacKeys($this->hmacServiceAccount);
        // Create test key.
        $this->accessId = '';
    }

    public function tearDown()
    {
        // Delete all HMAC keys.
        deleteAllHmacKeys($this->hmacServiceAccount);
    }

    private function deleteAllHmacKeys($hmacServiceAccount)
    {
        $storage = new StorageClient();
        $hmacKeys = $storage->hmacKeys($options);
        foreach ($hmacKeys as $hmacKey) {
            $hmacKey->update('INACTIVE');
            $hmacKey->delete();
        }
    }

    public function testHmacKeyList()
    {
        $this->commandTesterList->execute(
          [
              'projectId' => $this->projectId
          ],
          ['interactive' => false]
        );
        $outputString = <<<EOF
List List list

EOF;
        $this->expectOutputString($outputString);
    }

    /** @depends testHmacKeyList */
    public function testHmacKeyCreate()
    {
        $this->commandTesterCreate->execute(
        [
            'projectId' => $this->projectId,
            'serviceAccoutnEmail' => $this->serviceAccountEmail
        ],
        ['interactive' => false]
      );
        $outputString = <<<EOF
Create Create Create

EOF;
        $this->expectOutputString($outputString);
    }

    /** @depends testHmacKeyCreate */
    public function testHmacKeyGet()
    {
        $this->commandTesterManage->execute(
        [
            'projectId' => $this->projectId,
            'accessId' => $this->accessId,
            '--get' => true
        ],
        ['interactive' => false]
      );
        $outputString = <<<EOF
Create Create Create

EOF;
        $this->expectOutputString($outputString);
    }

    /** @depends testHmacKeyGet */
    public function testHmacKeyDeactivate()
    {
        $this->commandTesterManage->execute(
        [
            'projectId' => $this->projectId,
            'accessId' => $this->accessId,
            '--deactivate' => true
        ],
        ['interactive' => false]
      );
        $outputString = <<<EOF
Create Create Create

EOF;
        $this->expectOutputString($outputString);
    }

    /** @depends testHmacKeyDeactivate */
    public function testHmacKeyActivate()
    {
        $this->commandTesterManage->execute(
        [
            'projectId' => $this->projectId,
            'accessId' => $this->accessId,
            '--activate' => true
        ],
        ['interactive' => false]
      );
        $outputString = <<<EOF
Create Create Create

EOF;
        $this->expectOutputString($outputString);
    }

    /** @depends testHmacKeyActivate */
    public function testHmacKeyDelete()
    {
        $this->commandTesterManage->execute(
        [
            'projectId' => $this->projectId,
            'accessId' => $this->accessId,
            '--deactivate' => true
        ],
        ['interactive' => false]
      );
        $this->commandTesterManage->execute(
        [
            'projectId' => $this->projectId,
            'accessId' => $this->accessId,
            '--delete' => true
        ],
        ['interactive' => false]
      );
        $outputString = <<<EOF
Create Create Create

EOF;
        $this->expectOutputString($outputString);
    }
}
