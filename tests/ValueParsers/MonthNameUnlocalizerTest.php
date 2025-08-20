<?php

namespace ValueParsers\Test;

use PHPUnit\Framework\TestCase;
use ValueParsers\MonthNameUnlocalizer;

/**
 * @covers ValueParsers\MonthNameUnlocalizer
 *
 * @group DataValue
 * @group DataValueExtensions
 * @group ValueParsers
 *
 * @license GPL-2.0-or-later
 * @author Addshore
 * @author Thiemo Kreuz
 */
class MonthNameUnlocalizerTest extends TestCase {

	/**
	 * @dataProvider localizedDateProvider
	 */
	public function testUnlocalize(
		$date,
		$expected,
		array $replacements
	) {
		$unlocalizer = new MonthNameUnlocalizer( $replacements );

		$this->assertEquals( $expected, $unlocalizer->unlocalize( $date ) );
	}

	public function localizedDateProvider() {
		return array(
			// No replacements given
			array( '', [ '', null ], array( 0 => array() ) ),
			array( 'Jul', [ 'Jul', null ], array( 0 => array() ) ),

			// Longer strings do have higher priority
			array( 'Juli', [ 'July', null ], array(
				0 => array(
					'Jul' => 'bad',
					'Juli' => 'July',
			) ) ),
			array( 'Juli', [ 'July', null ], array(
				0 => array(
					'Juli' => 'July',
					'Jul' => 'bad',
			) ) ),

			// Do not mess with strings that are clearly not a valid date.
			array( 'July July', [ 'July July', null ], array(
				0 => array(
					'July' => 'bad',
			) ) ),

			// Do not mess with already unlocalized month names.
			array( 'July', [ 'July', null ], array(
				0 => array(
					'Jul' => 'July',
			) ) ),

			// But shortening is ok even if a substring looks like it's already unlocalized.
			array( 'July', [ 'Jul', null ], array(
				0 => array(
					'July' => 'Jul',
			) ) ),

			// Word boundaries currently do not prevent unlocalization on purpose.
			array( '1Jul2015', [ '1July2015', null ], array(
				0 => array(
					'Jul' => 'July',
			) ) ),
			array( '1stJulLastYear', [ '1stJulyLastYear', null ], array(
				0 => array(
					'Jul' => 'July',
			) ) ),

			// Capitalization is currently significant. This may need to depend on the languages.
			array( 'jul', [ 'jul', null ], array(
				0 => array(
					'Jul' => 'bad',
			) ) ),

			// Some translations (e.g. ko) just repeat the number of the month
			array( '2000', [ '2000', null ], array(
				0 => array(
					'2' => 'February',
			) ) ),
		);
	}

}
