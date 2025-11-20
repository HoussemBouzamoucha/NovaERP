<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    private const MAX_RECURSION_DEPTH = 2; // prevent infinite loops

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getField', [$this, 'getField']),
            new TwigFunction('getFields', [$this, 'getFields']),
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('humanize', [$this, 'humanize']),
            new TwigFilter('json_decode', [$this, 'jsonDecode']),
        ];
    }

    /**
     * Returns all property names of an entity
     */
    public function getFields($entity): array
    {
        $fields = [];
        $reflection = new \ReflectionClass($entity);
        foreach ($reflection->getProperties() as $property) {
            $fields[] = $property->getName();
        }
        return $fields;
    }

    /**
     * Returns a formatted value for a field, recursively handling nested objects
     */
    public function getField($entity, string $field, int $depth = 0)
    {
        $getter = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $field)));

        if (!method_exists($entity, $getter)) {
            return null;
        }

        $value = $entity->$getter();

        // Format DateTime
        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d H:i');
        }

        // Decode JSON strings
        if (is_string($value) && $this->isJson($value)) {
            $decoded = json_decode($value, true);
            if (is_iterable($decoded)) {
                return implode(', ', $decoded);
            }
            return $decoded;
        }

        // Iterables / Doctrine collections
        if (is_iterable($value)) {
            $arrayValue = is_array($value) ? $value : iterator_to_array($value);

            $arrayValue = array_map(function($v) use ($depth) {
                return $this->convertObjectToString($v, $depth + 1);
            }, $arrayValue);

            return implode(', ', $arrayValue);
        }

        // Single objects / related entities
        if (is_object($value)) {
            return $this->convertObjectToString($value, $depth + 1);
        }

        // Scalars
        return $value;
    }

    /**
     * Convert object to string, recursively handling nested related entities
     */
   private function convertObjectToString($object, int $depth = 0): string
{
    if ($depth > self::MAX_RECURSION_DEPTH) {
        return '[object]';
    }

    // If object can be cast to string
    if (method_exists($object, '__toString')) {
        return (string) $object;
    }

    // Try common display field
    foreach (['getName', 'getTitle', 'getEmail'] as $method) {
        if (method_exists($object, $method)) {
            return (string) $object->$method();
        }
    }

    // Otherwise, pick second property dynamically
    $reflection = new \ReflectionClass($object);
    $properties = $reflection->getProperties();

    if (isset($properties[1])) {
        $secondProperty = $properties[1]->getName();
        $getter = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $secondProperty)));

        if (method_exists($object, $getter)) {
            $value = $object->$getter();
            if (is_scalar($value)) {
                return (string) $value;
            }
            if (is_object($value) || is_iterable($value)) {
                return $this->convertObjectToString($value, $depth + 1);
            }
        }
    }

    // Fallback to id
    if (method_exists($object, 'getId')) {
        return (string) $object->getId();
    }

    return '[object]';
}




    /**
     * Check if a string is JSON
     */
    private function isJson(string $string): bool
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Humanize a field name
     */
    public function humanize(string $field): string
    {
        $field = str_replace('_', ' ', $field);
        $field = preg_replace('/at$/i', '', $field);
        return ucwords(trim($field));
    }

    /**
     * Twig filter for json_decode
     */
    public function jsonDecode(string $json)
    {
        $decoded = json_decode($json, true);
        return $decoded !== null ? $decoded : [];
    }
}
