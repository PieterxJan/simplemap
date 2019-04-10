<?php
/**
 * Maps for Craft CMS 3
 *
 * @link      https://ethercreative.co.uk
 * @copyright Copyright (c) 2019 Ether Creative
 */

namespace ether\maps\integrations\craftql;

use markhuot\CraftQL\Events\GetFieldSchema;

/**
 * Class GetCraftQLSchema
 *
 * @author  Ether Creative
 * @package ether\maps\integrations\craftql
 */
class GetCraftQLSchema
{

	/**
	 * @param GetFieldSchema $event
	 */
	public function handle (GetFieldSchema $event)
	{
		$event->handled = true;

		// Schema
		// ---------------------------------------------------------------------

		$parts = $event->schema->createObjectType('MapParts');
		$parts->addStringField('number');
		$parts->addStringField('address');
		$parts->addStringField('city');
		$parts->addStringField('postcode');
		$parts->addStringField('county');
		$parts->addStringField('state');
		$parts->addStringField('country');

		$map = $event->schema->createObjectType('Map');
		$map->addFloatField('lat');
		$map->addFloatField('lng');
		$map->addIntField('zoom');
		$map->addStringField('address');
		$map->addFloatField('distance');
		$map->addField('parts')->type($parts);

		$event->schema->addField($event->sender)->type($map);

		// Query
		// ---------------------------------------------------------------------

		$query = $event->query->createInputObjectType('MapQuery');
		$query->addStringArgument('location');
		$query->addStringArgument('country');
		$query->addFloatArgument('radius');
		/** @noinspection PhpUndefinedMethodInspection */
		$query->addEnumArgument('unit')->values([
			'mi' => 'Miles',
			'km' => 'Kilometres',
		]);

		$event->query->addArgument($event->sender)->type($query);

		// Mutation
		// ---------------------------------------------------------------------

		$mutationParts = $event->mutation->createInputObjectType('MapPartsInput');
		$mutationParts->addStringArgument('number');
		$mutationParts->addStringArgument('address');
		$mutationParts->addStringArgument('city');
		$mutationParts->addStringArgument('postcode');
		$mutationParts->addStringArgument('county');
		$mutationParts->addStringArgument('state');
		$mutationParts->addStringArgument('country');

		$mutation = $event->mutation->createInputObjectType('MapInput');
		$mutation->addFloatArgument('lat');
		$mutation->addFloatArgument('lng');
		$mutation->addIntArgument('zoom');
		$mutation->addStringArgument('address');
		$mutation->addFloatArgument('distance');
		$mutation->addArgument('parts')->type($mutationParts);

		$event->mutation->addArgument($event->sender)->type($mutation);
	}

}
