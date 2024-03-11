<?php

namespace Solr\Transformation;

class IndexURI extends AbstractTransformation
{
    public function getLabel(): string
    {
        return 'Index URI and Label'; // @translate
    }

    public function transform(array $values, array $transformationData): array
    {
        $transformedValues = [];
        foreach ($values as $value) {
            if ($uri = $value->uri()) {
                array_push($transformedValues, $value->value(), $uri);
            } else {
                $transformedValues[] = $value;
            }
        }
        return $transformedValues;
    }
}
