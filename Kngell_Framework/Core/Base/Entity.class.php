<?php

declare(strict_types=1);

class Entity extends AbstractEntity
{
    // /**
    //  * Sanitize
    //  * =========================================================.
    //  * @param array $dirtyData
    //  */
    // public function sanitize(array $dirtyData) : void
    // {
    //     if (empty($dirtyData)) {
    //         throw new BaseInvalidArgumentException('No data was submitted');
    //     }
    //     if (is_array($dirtyData)) {
    //         foreach ($this->cleanData($dirtyData) as $key => $value) {
    //             $this->$key = $value;
    //         }
    //     }
    // }

    // public function getEntityData() : array
    // {
    //     $data = [];
    //     foreach ($this->getInitializedAttributes() as $key => $value) {
    //         $data[$key] = $value;
    //     }
    //     return $data;
    // }

    public function getFieldValue(string $field) : mixed
    {
        $method = $this->getGetters($this->regenerateField($field));
        return $this->reflectionclass()->getMethod($method, $this)->invoke($this, $method);
    }

    public function getAllAttributes() : array
    {
        return array_column($this->reflectionInstance()->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PRIVATE), 'name');
    }

    public function isInitialized(string $field) : bool
    {
        return $this->reflectionClass()->isInitialized($this->regenerateField($field), $this);
    }

    public function count() : int
    {
        return count($this->getInitializedAttributes());
    }

    public function getColId(string $withDocComment = 'id') :  string
    {
        $props = $this->getAllAttributes();
        foreach ($props as $field) {
            $docs = $this->getPropertyComment($field);
            if ($docs == $withDocComment) {
                return $field;
                exit;
            }
        }
        return '';
    }

    public function exists(string $field) : bool
    {
        return $this->reflectionInstance()->hasProperty($field);
    }

    public function getInitializedAttributes(bool $output = false) : array
    {
        $properties = [];
        foreach ($this->getAllAttributes() as $field) {
            $rp = $this->reflectionInstance()->getProperty($field);
            if ($rp->isInitialized($this)) {
                if ($rp->getType()->getName() === 'DateTimeInterface') {
                    $properties[$field] = $rp->getValue($this)->format('Y-m-d H:i:s');
                } else {
                    if ($output) {
                        if ($rp->getType()->getName() === 'string') {
                            $properties[$field] = $rp->getValue($this);
                        } else {
                            $properties[$field] = $rp->getValue($this);
                        }
                    } else {
                        $properties[$field] = $rp->getValue($this);
                    }
                }
            }
        }
        return $properties;
    }

    public function getFields(string $field) : string
    {
        return $this->reflectionInstance()->getProperty($field)->getName();
    }

    public function getFieldWithDoc(string $withDocComment) : string
    {
        return $this->getColID($withDocComment);
    }

    public function getPropertyComment(string $field) : string
    {
        $propertyComment = $this->reflectionInstance()->getProperty($field)->getDocComment();

        return $this->filterPropertyComment($propertyComment);
    }

    public function getObject() : object
    {
        return (object) $this->getInitializedAttributes();
    }

    public function assign(array $params) : self|bool
    {
        $attrs = $this->getAllAttributes();
        if (count($params) < count($attrs)) {
            return $this->assingParams($attrs, $params);
        } else {
            return $this->assingEntity($attrs, $params);
        }
        return $this;
    }

    public function getIdCondition() : array|bool
    {
        $colId = $this->getColId();
        if ($this->isInitialized($colId)) {
            return [$colId => $this->getFieldValue($colId)];
            // if ($this->disablePrimaryKey()) {
            // }
        }
        return false;
    }

    // /**
    //  * Get Html Decode texte
    //  * ====================================================================================.
    //  * @param string $str
    //  * @return string
    //  */
    // public function htmlDecode(?string $str) : ?string
    // {
    //     return ! empty($str) ? htmlspecialchars_decode(html_entity_decode($str), ENT_QUOTES) : '';
    // }

    // public function getContentOverview(string $content):string
    // {
    //     // $headercontent = preg_match_all('|<h[^>]+>(.*)</h[^>]+>|iU', htmlspecialchars_decode($content, ENT_NOQUOTES), $headings);
    //     return substr(strip_tags($this->htmlDecode($content)), 0, 200) . '...';
    // }

    public function delete(?string $field = null) : self
    {
        $reflectionProperty = $this->reflectionInstance()->getProperty($field);
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this, null);
        return $this;
    }

    public function isPropertiesSet() : bool
    {
        $properties = $this->getInitializedAttributes();
        return ! empty($properties);
    }

    public function empty() : self
    {
        $attrs = $this->getInitializedAttributes();
        foreach ($attrs as $attr) {
            $this->delete($attr);
        }
        return $this;
    }

    public function disablePrimaryKey() : self|bool
    {
        $colID = $this->getColId();
        if (! $this->isInitialized($colID)) {
            return $this;
        }
        try {
            $p = $this->reflectionClass()->reflectionObj($this)->getMethod('delete')->invokeArgs($this, [$colID]);
            return $this;
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Clean Data
     * ==========================================================.
     * @param array $dirtyData
     * @return array
     */
    private function cleanData(array $dirtyData) : array
    {
        $cleanData = Sanitizer::clean($dirtyData);
        if ($cleanData) {
            return $cleanData;
        }

        return [];
    }
}