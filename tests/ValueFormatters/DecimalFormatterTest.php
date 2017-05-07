<?php

namespace ValueFormatters\Test;

use DataValues\DecimalValue;
use ValueFormatters\DecimalFormatter;
use ValueFormatters\FormatterOptions;
use ValueFormatters\NumberLocalizer;

/**
 * @covers ValueFormatters\DecimalFormatter
 *
 * @group ValueFormatters
 * @group DataValueExtensions
 *
 * @license GPL-2.0+
 * @author Daniel Kinzler
 */
class DecimalFormatterTest extends ValueFormatterTestBase {

	/**
	 * @deprecated since DataValues Interfaces 0.2, just use getInstance.
	 */
	protected function getFormatterClass() {
		throw new \LogicException( 'Should not be called, use getInstance' );
	}

	/**
	 * @see ValueFormatterTestBase::getInstance
	 *
	 * @param FormatterOptions|null $options
	 *
	 * @return DecimalFormatter
	 */
	protected function getInstance( FormatterOptions $options = null ) {
		return new DecimalFormatter( $options );
	}

	/**
	 * @see ValueFormatterTestBase::validProvider
	 */
	public function validProvider() {
		$optionsForceSign = new FormatterOptions( array(
			DecimalFormatter::OPT_FORCE_SIGN => true
		) );

		$decimals = array(
			'+0' => array( '0', null ),
			'+0.0' => array( '0.0', null ),
			'-0.0130' => array( '-0.0130', null ),
			'+10000.013' => array( '10000.013', null ),
			'+20000.4' => array( '+20000.4', $optionsForceSign ),
			'-12' => array( '-12', null )
		);

		$argLists = array();
		foreach ( $decimals as $input => $args ) {
			$inputValue = new DecimalValue( $input );

			$argLists[$input] = array_merge( array( $inputValue ), $args );
		}

		return $argLists;
	}

	public function testLocalization() {
		$localizer = $this->getMock( NumberLocalizer::class );

		$localizer->expects( $this->once() )
			->method( 'localizeNumber' )
			->will( $this->returnCallback( function ( $number ) {
				return "n:$number";
			} ) );

		$value = new DecimalValue( '+12345' );
		$formatter = new DecimalFormatter( null, $localizer );

		$this->assertSame( 'n:12345', $formatter->format( $value ) );
	}

}
