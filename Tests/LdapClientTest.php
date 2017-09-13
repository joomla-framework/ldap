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
			'host' => getenv('LDAP_HOST') ?: '127.0.0.1',
			'port' => getenv('LDAP_PORT') ?: '3389',
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
	public function testConnect()
	{
		$this->assertTrue($this->object->connect());
	}

	/**
	 * @covers  Joomla\Ldap\Ldap::setDn
	 * @uses    Joomla\Ldap\Ldap::getDn
	 */
	public function testSetDnWithNoUserDn()
	{
		$dn = 'cn=admin,dc=joomla,dc=org';

		$this->object->setDn($dn);

		$this->assertSame($dn, $this->object->getDn());
	}

	/**
	 * @covers  Joomla\Ldap\Ldap::setDn
	 * @uses    Joomla\Ldap\Ldap::getDn
	 */
	public function testSetDnWithUserDn()
	{
		$this->object->setDn('uid=[username],cn=admin,dc=joomla,dc=org');
		$this->object->setDn('admin');

		$this->assertSame('uid=admin,cn=admin,dc=joomla,dc=org', $this->object->getDn());
	}

	/**
	 * @covers  Joomla\Ldap\Ldap::getDn
	 */
	public function testGetDn()
	{
		$this->assertNull($this->object->getDn());
	}

	/**
	 * Test...
	 *
	 * @todo Implement testAnonymous_bind().
	 *
	 * @return void
	 */
	public function testAnonymous_bind()
	{
		if (!$this->object->connect())
		{
			$this->markTestSkipped('Could not connect to LDAP server');
		}

		var_dump($this->object->anonymous_bind());
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test...
	 *
	 * @todo Implement testBind().
	 *
	 * @return void
	 */
	public function testBind()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
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
