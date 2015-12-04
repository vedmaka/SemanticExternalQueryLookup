<?php

namespace SEQL\Tests;

use SEQL\DataValueDeserializer;
use SMW\DIWikiPage;
use SMW\DIProperty;
use SMWDITime as DITime;

/**
 * @covers \SEQL\DataValueDeserializer
 * @group semantic-external-query-lookup
 *
 * @license GNU GPL v2+
 * @since 1.0
 *
 * @author mwjames
 */
class DataValueDeserializerTest extends \PHPUnit_Framework_TestCase {

	public function testCanConstruct() {

		$this->assertInstanceOf(
			'\SEQL\DataValueDeserializer',
			new DataValueDeserializer( 'foo' )
		);
	}

	public function testNewDiWikiPage() {

		$instance = new DataValueDeserializer( 'foo' );

		$value = array(
			'namespace' => NS_MAIN,
			'fulltext'  => 'abc def'
		);

		$this->assertEquals(
			new DIWikiPage( 'Foo:abc_def', NS_MAIN ),
			$instance->newDiWikiPage( $value )
		);
	}

	public function testTryNewDiWikiPageForInvalidSeralization() {

		$instance = new DataValueDeserializer( 'foo' );

		$this->assertFalse(
			$instance->newDiWikiPage( array( 'Foo' ) )
		);
	}

	public function testNewTimeValueForOutOfRangeTimestamp() {

		$instance = new DataValueDeserializer( 'foo' );

		$property = new DIProperty( 'Bar' );
		$property->setPropertyTypeId( '_dat' );

		$this->assertNotEquals(
			DITime::doUnserialize( '2/-200' ),
			$instance->newDataValueFrom( $property, '-2000101000000' )
		);
	}

	public function testNewTimeValueForRawTimeFromat() {

		$instance = new DataValueDeserializer( 'foo' );

		$property = new DIProperty( 'Bar' );
		$property->setPropertyTypeId( '_dat' );

		$this->assertEquals(
			DITime::doUnserialize( '2/-200' ),
			$instance->newDataValueFrom( $property, array( 'raw' => '2/-200' ) )->getDataItem()
		);
	}

}
