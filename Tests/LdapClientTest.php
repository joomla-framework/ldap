<?php
/**
 * @copyright  Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Ldap\Tests;

use Joomla\Ldap\LdapClient;
use Joomla\Registry\Registry;
use PHPUnit\Framework\TestCase;

/**
 * Test class for Joomla\Ldap\LdapClient.
 */
class LdapClientTest extends TestCase
{
	/**
	 * @var  LdapClient
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return  void
	 */
	protected function setUp()
	{
		parent::setUp();

		$data = array(
			'host'       => getenv('LDAP_HOST') ?: '127.0.0.1',
			'port'       => getenv('LDAP_PORT') ?: '3389',
			'use_ldapV3' => (bool) getenv('LDAP_V3') ?: true,
		);

		$this->object = new LdapClient(new Registry($data));
	}

	/**
	 * Tears down the fixture, for example, close a network connection.
	 * This method is called after a test is executed.
	 *
	 * @return  void
	 */
	protected function tearDown()
	{
		unset($this->object);

		parent::tearDown();
	}

	/**
	 * @covers  Joomla\Ldap\Ldap::connect
	 */
	public function testTheConnectionIsOpened()
	{
		$this->assertTrue($this->object->connect());
	}

	/**
	 * @testdox  The DN is set when there is no user DN
	 *
	 * @covers  Joomla\Ldap\Ldap::setDn
	 * @uses    Joomla\Ldap\Ldap::getDn
	 */
	public function testTheDnIsSetWhenThereIsNoUserDn()
	{
		$dn = 'cn=admin,dc=joomla,dc=org';

		$this->object->setDn($dn);

		$this->assertSame($dn, $this->object->getDn());
	}

	/**
	 * @testdox  The DN is set when there is a user DN
	 *
	 * @covers  Joomla\Ldap\Ldap::setDn
	 * @uses    Joomla\Ldap\Ldap::getDn
	 */
	public function testTheDnIsSetWhenThereIsAUserDn()
	{
		$this->object->users_dn = 'uid=[username],cn=admin,dc=joomla,dc=org';

		$this->object->setDn('admin');

		$this->assertSame('uid=admin,cn=admin,dc=joomla,dc=org', $this->object->getDn());
	}

	/**
	 * @testdox  The DN is retrieved
	 *
	 * @covers  Joomla\Ldap\Ldap::getDn
	 */
	public function testTheDnIsRetrieved()
	{
		$this->assertNull($this->object->getDn());
	}

	/**
	 * @testdox  The connection is bound to the LDAP server anonymously
	 *
	 * @covers  Joomla\Ldap\Ldap::anonymous_bind
	 */
	public function testAnonymousBinding()
	{
		if (!$this->object->connect())
		{
			$this->markTestSkipped('Could not connect to LDAP server');
		}

		$this->assertTrue($this->object->anonymous_bind(), 'LDAP connection failed: ' . $this->object->getErrorMsg());
	}

	/**
	 * @testdox  The connection is bound to the LDAP server
	 *
	 * @covers  Joomla\Ldap\Ldap::bind
	 * @uses    Joomla\Ldap\Ldap::connect
	 * @uses    Joomla\Ldap\Ldap::setDn
	 */
	public function testBinding()
	{
		if (!$this->object->connect())
		{
			$this->markTestSkipped('Could not connect to LDAP server');
		}

		$this->object->setDn('cn=admin,dc=joomla,dc=org');

		$this->assertTrue($this->object->bind(null, 'joomla'), 'LDAP connection failed: ' . $this->object->getErrorMsg());
	}

	/**
	 * Test...
	 *
	 * @todo Implement testSimple_search().
	 *
	 * @return void
	 */
	public function testSimple_search()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test...
	 *
	 * @todo Implement testSearch().
	 *
	 * @return void
	 */
	public function testSearch()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test...
	 *
	 * @todo Implement testReplace().
	 *
	 * @return void
	 */
	public function testReplace()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test...
	 *
	 * @todo Implement testModify().
	 *
	 * @return void
	 */
	public function testModify()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test...
	 *
	 * @todo Implement testRemove().
	 *
	 * @return void
	 */
	public function testRemove()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test...
	 *
	 * @todo Implement testCompare().
	 *
	 * @return void
	 */
	public function testCompare()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test...
	 *
	 * @todo Implement testRead().
	 *
	 * @return void
	 */
	public function testRead()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test...
	 *
	 * @todo Implement testDelete().
	 *
	 * @return void
	 */
	public function testDelete()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test...
	 *
	 * @todo Implement testCreate().
	 *
	 * @return void
	 */
	public function testCreate()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test...
	 *
	 * @todo Implement testAdd().
	 *
	 * @return void
	 */
	public function testAdd()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test...
	 *
	 * @todo Implement testRename().
	 *
	 * @return void
	 */
	public function testRename()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test...
	 *
	 * @todo Implement testGetErrorMsg().
	 *
	 * @return void
	 */
	public function testGetErrorMsg()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test...
	 *
	 * @todo Implement testIpToNetAddress().
	 *
	 * @return void
	 */
	public function testIpToNetAddress()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test...
	 *
	 * @todo Implement testLdapNetAddr().
	 *
	 * @return void
	 */
	public function testLdapNetAddr()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test...
	 *
	 * @todo Implement testGeneratePassword().
	 *
	 * @return void
	 */
	public function testGeneratePassword()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
}
