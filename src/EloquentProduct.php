<?php namespace Jiro\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Collection;
use Jiro\Product\Property\PropertyInterface;

/**
 * Catalog product model.
 *
 * @author Andrew McLagan <andrewmclagan@gmail.com>
 */

class EloquentProduct extends Model implements ProductInterface
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * White list of fillable attributes.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'slug', 
        'description', 
        'meta_keywords',
        'meta_description',
        'available_on'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (!array_key_exists('available_on', $attributes))
        {
            $this->setAvailableOn(new \Carbon\Carbon);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * {@inheritdoc}
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetaKeywords()
    {
        return $this->meta_keywords;
    }

    /**
     * {@inheritdoc}
     */
    public function setMetaKeywords($meta_keywords)
    {
        $this->meta_keywords = $meta_keywords;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetaDescription()
    {
        return $this->meta_description;
    }

    /**
     * {@inheritdoc}
     */
    public function setMetaDescription($meta_description)
    {
        $this->meta_description = $meta_description;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isAvailable()
    {
        return new \DateTime() >= $this->available_on;
    }

    /**
     * {@inheritdoc}
     */
    public function getAvailableOn()
    {
        return $this->available_on;
    }

    /**
     * {@inheritdoc}
     */
    public function setAvailableOn(\DateTime $available_on = null)
    {
        $this->available_on = $available_on;

        return $this;
    }

    /** 
     * {@inheritdoc}
     */
    public function properties()
    {
        return $this->hasMany('Jiro\Product\Property\EloquentPropertyValue','product_id');
    }

    /** 
     * {@inheritdoc}
     */
    public function setProperties($properties)
    {
        if (!$properties instanceof Collection)
        {
            throw new \InvalidArgumentException('Properties must be an instance of Illuminate\Database\Eloquent\Collection');
        }

        $this->properties()->saveMany($properties->all());

        return $this;        
    }

    /** 
     * {@inheritdoc}
     */
    public function addProperty(PropertyValueInterface $property)
    {             
        $this->properties()->save($property);

        return $this;        
    }

    /** 
     * {@inheritdoc}
     */
    public function removeProperty(PropertyInterface $property)
    {
        if ($this->hasProperty($property)) 
        { 
            $property->products()->detach($this->getKey());
        }

        return $this;        
    }

    /** 
     * {@inheritdoc}
     */
    public function hasProperty(PropertyInterface $property)
    {
        
        return $this->properties->contains($property);        
    }

    /** 
     * {@inheritdoc}
     */
    public function hasPropertyByName($propertyName)
    {
        foreach ($this->properties as $property) 
        {
            if($property->getName() === $propertyName)
            {
                return true;
            }
        }

        return false;        
    }

    /** 
     * {@inheritdoc}
     */
    public function getPropertyByName($propertyName)
    {
        foreach ($this->properties as $property) 
        {
            if ($property->getName() === $propertyName) 
            {
                return $property;
            }
        }

        return null;
    }    
}
