<?php

/*
 * Copyright BibLibre, 2016-2017
 *
 * This software is governed by the CeCILL license under French law and abiding
 * by the rules of distribution of free software.  You can use, modify and/ or
 * redistribute the software under the terms of the CeCILL license as circulated
 * by CEA, CNRS and INRIA at the following URL "http://www.cecill.info".
 *
 * As a counterpart to the access to the source code and rights to copy, modify
 * and redistribute granted by the license, users are provided only with a
 * limited warranty and the software's author, the holder of the economic
 * rights, and the successive licensors have only limited liability.
 *
 * In this respect, the user's attention is drawn to the risks associated with
 * loading, using, modifying and/or developing or reproducing the software by
 * the user in light of its specific status of free software, that may mean that
 * it is complicated to manipulate, and that also therefore means that it is
 * reserved for developers and experienced professionals having in-depth
 * computer knowledge. Users are therefore encouraged to load and test the
 * software's suitability as regards their requirements in conditions enabling
 * the security of their systems and/or data to be ensured and, more generally,
 * to use and operate it in the same conditions as regards security.
 *
 * The fact that you are presently reading this means that you have had
 * knowledge of the CeCILL license and that you accept its terms.
 */

namespace Solr\ValueExtractor;

use Omeka\Api\Manager as ApiManager;
use Omeka\Api\Representation\AbstractResourceRepresentation;
use Solr\Value\DateTimeValue;
use Stringable;

class ItemSetValueExtractor extends AbstractValueExtractor
{
    protected $api;

    public function setApiManager(ApiManager $api)
    {
        $this->api = $api;
    }

    public function getLabel()
    {
        return 'Item Set';
    }

    public function getAvailableFields()
    {
        $fields = [
            'created' => [
                'label' => 'Created',
            ],
            'modified' => [
                'label' => 'Modified',
            ],
            'is_public' => [
                'label' => 'Is public',
            ],
            'is_open' => [
                'label' => 'Is open',
            ],
            'resource_class' => [
                'label' => 'Resource class',
            ],
            'resource_template' => [
                'label' => 'Resource template',
            ],
        ];

        $properties = $this->api->search('properties')->getContent();
        foreach ($properties as $property) {
            $term = $property->term();
            $fields[$term]['label'] = $term;
        }

        $params = ['fields' => $fields];
        $params = $this->triggerEvent('solr.value_extractor.fields', null, $params);
        $fields = $params['fields'];

        return $fields;
    }

    public function extractValue(AbstractResourceRepresentation $itemSet, $field, array $settings): Stringable|array|string|int|float|bool
    {
        $params = ['field' => $field, 'settings' => $settings, 'value' => null];
        $params = $this->triggerEvent('solr.value_extractor.extract_value', $itemSet, $params);
        if (isset($params['value'])) {
            return $params['value'];
        }

        if ($field === 'created') {
            return DateTimeValue::createFromInterface($itemSet->created());
        }

        if ($field === 'modified') {
            $modified = $itemSet->modified();
            return $modified ? DateTimeValue::createFromInterface($modified) : [];
        }

        if ($field === 'is_public') {
            return $itemSet->isPublic();
        }

        if ($field === 'is_open') {
            return $itemSet->isOpen();
        }

        if ($field === 'resource_class') {
            $resourceClass = $itemSet->resourceClass();
            return $resourceClass ? $resourceClass->term() : null;
        }

        if ($field === 'resource_template') {
            $resourceTemplate = $itemSet->resourceTemplate();
            return $resourceTemplate ? $resourceTemplate->label() : null;
        }
        // Only return public values
        return array_filter($itemSet->value($field, ['all' => true, 'default' => []]), fn($v) => $v->isPublic());
    }
}
