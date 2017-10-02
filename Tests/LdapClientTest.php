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

		$v3 = getenv('LDAP_V3');

		$data = array(
			'host'       => getenv('LDAP_HOST') ?: '127.0.0.1',
			'port'       => getenv('LDAP_PORT') ?: '3389',
			'use_ldapV3' => $v3 === false ? true : (bool) $v3,
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
		$this->object->users_dn = 'cn=[username],dc=joomla,dc=org';

		$this->object->setDn('admin');

		$this->assertSame('cn=admin,dc=joomla,dc=org', $this->object->getDn());
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

		$this->object->users_dn = 'cn=[username],dc=joomla,dc=org';

		$this->assertTrue($this->object->bind('admin', 'joomla'), 'LDAP connection failed: ' . $this->object->getErrorMsg());
	}

	/**
	 * @testdox  A simple search is performed
	 *
	 * @covers  Joomla\Ldap\Ldap::simple_search
	 * @uses    Joomla\Ldap\Ldap::bind
	 * @uses    Joomla\Ldap\Ldap::connect
	 * @uses    Joomla\Ldap\Ldap::search
	 * @uses    Joomla\Ldap\Ldap::setDn
	 */
	public function testSimpleSearch()
	{
		if (!$this->object->connect())
		{
			$this->markTestSkipped('Could not connect to LDAP server');
		}

		$this->object->base_dn  = 'dc=joomla,dc=org';
		$this->object->users_dn = 'cn=[username],dc=joomla,dc=org';

		if (!$this->object->bind('admin', 'joomla'))
		{
			$this->markTestSkipped('Could not bind to LDAP server');
		}

		$this->assertCount(1, $this->object->simple_search('objectclass=person'), 'The search did not return the expected number of results');
	}

	/**
	 * @testdox  A search is performed without an override of the base DN
	 *
	 * @covers  Joomla\Ldap\Ldap::search
	 * @uses    Joomla\Ldap\Ldap::bind
	 * @uses    Joomla\Ldap\Ldap::connect
	 * @uses    Joomla\Ldap\Ldap::setDn
	 */
	public function testSearchWithoutDnOverride()
	{
		if (!$this->object->connect())
		{
			$this->markTestSkipped('Could not connect to LDAP server');
		}

		$this->object->base_dn  = 'dc=joomla,dc=org';
		$this->object->users_dn = 'cn=[username],dc=joomla,dc=org';

		if (!$this->object->bind('admin', 'joomla'))
		{
			$this->markTestSkipped('Could not bind to LDAP server');
		}

		$this->assertCount(1, $this->object->search(array('(objectclass=person)')), 'The search did not return the expected number of results');
	}

	/**
	 * @testdox  A search is performed with an override of the base DN
	 *
	 * @covers  Joomla\Ldap\Ldap::search
	 * @uses    Joomla\Ldap\Ldap::bind
	 * @uses    Joomla\Ldap\Ldap::connect
	 * @uses    Joomla\Ldap\Ldap::setDn
	 */
	public function testSearchWithDnOverride()
	{
		if (!$this->object->connect())
		{
			$this->markTestSkipped('Could not connect to LDAP server');
		}

		$this->object->users_dn = 'cn=[username],dc=joomla,dc=org';

		if (!$this->object->bind('admin', 'joomla'))
		{
			$this->markTestSkipped('Could not bind to LDAP server');
		}

		$this->assertCount(1, $this->object->search(array('(objectclass=person)'), 'dc=joomla,dc=org'), 'The search did not return the expected number of results');
	}

	/**
	 * @testdox  An attribute is replaced for the given user DN
	 *
	 * @covers  Joomla\Ldap\Ldap::replace
	 * @uses    Joomla\Ldap\Ldap::bind
	 * @uses    Joomla\Ldap\Ldap::connect
	 * @uses    Joomla\Ldap\Ldap::setDn
	 */
	public function testReplace()
	{
		if (!$this->object->connect())
		{
			$this->markTestSkipped('Could not connect to LDAP server');
		}

		$this->object->base_dn  = 'dc=joomla,dc=org';
		$this->object->users_dn = 'cn=[username],dc=joomla,dc=org';

		if (!$this->object->bind('admin', 'joomla'))
		{
			$this->markTestSkipped('Could not bind to LDAP server');
		}

		$this->assertTrue($this->object->replace('cn=Michael Babker,dc=joomla,dc=org', array('mail' => 'michael@joomla.org')), 'The attribute was not replaced');

		// Reset
		$this->object->replace('cn=Michael Babker,dc=joomla,dc=org', array('mail' => 'michael.babker@joomla.org'));
	}

	/**
	 * @testdox  An attribute is modified for the given user DN
	 *
	 * @covers  Joomla\Ldap\Ldap::modify
	 * @uses    Joomla\Ldap\Ldap::bind
	 * @uses    Joomla\Ldap\Ldap::connect
	 * @uses    Joomla\Ldap\Ldap::setDn
	 */
	public function testModify()
	{
		if (!$this->object->connect())
		{
			$this->markTestSkipped('Could not connect to LDAP server');
		}

		$this->object->base_dn  = 'dc=joomla,dc=org';
		$this->object->users_dn = 'cn=[username],dc=joomla,dc=org';

		if (!$this->object->bind('admin', 'joomla'))
		{
			$this->markTestSkipped('Could not bind to LDAP server');
		}

		$this->assertTrue($this->object->modify('cn=Michael Babker,dc=joomla,dc=org', array('mail' => 'michael@joomla.org')), 'The attribute was not modified');

		// Reset
		$this->object->modify('cn=Michael Babker,dc=joomla,dc=org', array('mail' => 'michael.babker@joomla.org'));
	}

	/**
	 * @testdox  An attribute is removed from the given user DN
	 *
	 * @covers  Joomla\Ldap\Ldap::remove
	 * @uses    Joomla\Ldap\Ldap::add
	 * @uses    Joomla\Ldap\Ldap::bind
	 * @uses    Joomla\Ldap\Ldap::connect
	 * @uses    Joomla\Ldap\Ldap::setDn
	 */
	public function testRemove()
	{
		if (!$this->object->connect())
		{
			$this->markTestSkipped('Could not connect to LDAP server');
		}

		$this->object->base_dn  = 'dc=joomla,dc=org';
		$this->object->users_dn = 'cn=[username],dc=joomla,dc=org';

		if (!$this->object->bind('admin', 'joomla'))
		{
			$this->markTestSkipped('Could not bind to LDAP server');
		}

		$this->assertTrue($this->object->remove('cn=Michael Babker,dc=joomla,dc=org', array('mail' => 'michael.babker@joomla.org')), 'The attribute was not removed');

		// Reset
		$this->object->add('cn=Michael Babker,dc=joomla,dc=org', array('mail' => 'michael.babker@joomla.org'));
	}

	/**
	 * @testdox  An attribute is compared for a given value to the user DN
	 *
	 * @covers  Joomla\Ldap\Ldap::compare
	 * @uses    Joomla\Ldap\Ldap::bind
	 * @uses    Joomla\Ldap\Ldap::connect
	 * @uses    Joomla\Ldap\Ldap::setDn
	 */
	public function testCompare()
	{
		if (!$this->object->connect())
		{
			$this->markTestSkipped('Could not connect to LDAP server');
		}

		$this->object->base_dn  = 'dc=joomla,dc=org';
		$this->object->users_dn = 'cn=[username],dc=joomla,dc=org';

		if (!$this->object->bind('admin', 'joomla'))
		{
			$this->markTestSkipped('Could not bind to LDAP server');
		}

		$this->assertTrue($this->object->compare('cn=Michael Babker,dc=joomla,dc=org', 'mail', 'michael.babker@joomla.org'), 'The attribute value is not in the expected state');
	}

	/**
	 * @testdox  A DN is read from the server and the attributes returned
	 *
	 * @covers  Joomla\Ldap\Ldap::read
	 * @uses    Joomla\Ldap\Ldap::bind
	 * @uses    Joomla\Ldap\Ldap::connect
	 * @uses    Joomla\Ldap\Ldap::setDn
	 */
	public function testRead()
	{
		if (!$this->object->connect())
		{
			$this->markTestSkipped('Could not connect to LDAP server');
		}

		$this->object->base_dn  = 'dc=joomla,dc=org';
		$this->object->users_dn = 'cn=[username],dc=joomla,dc=org';

		if (!$this->object->bind('admin', 'joomla'))
		{
			$this->markTestSkipped('Could not bind to LDAP server');
		}

		$result = $this->object->read('(objectclass=person),cn=Michael Babker,dc=joomla,dc=org');

		$this->assertSame(1, $result['count'], 'The expected number of entries were not read');
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
