<?php

namespace Solr\Transformation;

class ContributorFashion extends AbstractTransformation
{
    public function getLabel(): string
    {
        return 'Omit indexing FIT entities in the contributor field'; // @translate
    }

    public function transform(array $values, array $transformationData): array
    {
        $transformedValues = [];
        foreach ($values as $value) {
            if (!str_contains(strtolower((string) $value), strtolower("Fashion Institute of Technology"))) {
                $transformedValues[] = $value;
            }
        }
        return $transformedValues;
    }
}
