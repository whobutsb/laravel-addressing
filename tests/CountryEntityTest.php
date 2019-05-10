<?php

namespace Galahad\LaravelAddressing\Tests;

use CommerceGuys\Addressing\AddressFormat\AddressFormatRepository;
use CommerceGuys\Addressing\Country\CountryRepository;
use CommerceGuys\Addressing\Subdivision\SubdivisionRepository;
use Galahad\LaravelAddressing\Entity\Country;
use Galahad\LaravelAddressing\Entity\Subdivision;

class CountryEntityTest extends TestCase
{
	/**
	 * @var \Galahad\LaravelAddressing\Entity\Country
	 */
	protected $country;
	
	/**
	 * @var string[]
	 */
	protected $test_state_codes;
	
	protected function setUp() : void
	{
		parent::setUp();
		
		// TODO: Load from container, probably
		$this->country = new Country(
			(new CountryRepository())->get('US'),
			new SubdivisionRepository(),
			new AddressFormatRepository()
		);
		
		$this->test_state_codes = json_decode(file_get_contents(__DIR__.'/test-us-states.json'), true);
	}
	
	public function test_it_should_pass_getters_down_to_the_underlying_country_instance() : void
	{
		$timezones = [
			'America/Adak',
			'America/Anchorage',
			'America/Boise',
			'America/Chicago',
			'America/Denver',
			'America/Detroit',
			'America/Indiana/Indianapolis',
			'America/Indiana/Knox',
			'America/Indiana/Marengo',
			'America/Indiana/Petersburg',
			'America/Indiana/Tell_City',
			'America/Indiana/Vevay',
			'America/Indiana/Vincennes',
			'America/Indiana/Winamac',
			'America/Juneau',
			'America/Kentucky/Louisville',
			'America/Kentucky/Monticello',
			'America/Los_Angeles',
			'America/Menominee',
			'America/Metlakatla',
			'America/New_York',
			'America/Nome',
			'America/North_Dakota/Beulah',
			'America/North_Dakota/Center',
			'America/North_Dakota/New_Salem',
			'America/Phoenix',
			'America/Sitka',
			'America/Yakutat',
			'Pacific/Honolulu',
		];
		
		$this->assertEquals('US', $this->country->getCountryCode());
		$this->assertEquals('United States', $this->country->getName());
		$this->assertEquals('USA', $this->country->getThreeLetterCode());
		$this->assertEquals(840, $this->country->getNumericCode());
		$this->assertEquals('USD', $this->country->getCurrencyCode());
		$this->assertEquals($timezones, $this->country->getTimezones());
		$this->assertEquals('en', $this->country->getLocale());
	}
	
	public function test_it_should_be_able_to_list_its_subdivisions() : void
	{
		foreach ($this->country->subdivisions() as $administrative_area) {
			/** @var Subdivision $administrative_area */
			$this->assertInstanceOf(Subdivision::class, $administrative_area);
			$this->assertContains($administrative_area->getCode(), $this->test_state_codes);
		}
	}
	
	public function test_it_should_be_able_to_load_a_specific_subdivision() : void
	{
		foreach ($this->test_state_codes as $code) {
			$this->assertInstanceOf(Subdivision::class, $this->country->subdivision($code));
		}
	}
	
	public function test_it_returns_null_on_invalid_subdivisions() : void
	{
		$this->assertNull($this->country->subdivision('XX'));
	}
	
	public function test_a_subdivision_can_be_loaded_by_name() : void
	{
		$this->assertInstanceOf(Subdivision::class, $this->country->subdivisionByName('Pennsylvania'));
	}
	
	public function test_it_identifies_the_administrative_area_and_locality_names() : void
	{
		$this->assertEquals('state', $this->country->getAdministrativeAreaType());
		$this->assertEquals('city', $this->country->getLocalityType());
	}
}
