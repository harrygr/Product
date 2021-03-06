<?php namespace Jiro\Product\Option;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jiro\Product\Option\OptionInterface;

/**
 * Product option default implementation.
 *
 * @author Andrew McLagan <andrewmclagan@gmail.com>
 */

class EloquentOption extends Model implements OptionInterface
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'options';

    /**
     * White list of fillable attributes.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'presentation', 
    ];

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
    public function getPresentation()
    {
        return $this->presentation;
    }

    /**
     * {@inheritdoc}
     */
    public function setPresentation($presentation)
    {
        $this->presentation = $presentation;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function values()
    {
        return $this->hasMany('Jiro\Product\Option\EloquentOptionValue', 'option_id');
    }

    /**
     * {@inheritdoc}
     */
    public function setValues($values)
    {
        $this->values()->saveMany($values);
        
        return $this;       
    }

    /**
     * {@inheritdoc}
     */
    public function addValue(OptionValueInterface $value)
    {    
        $this->values()->save($value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeValue(OptionValueInterface $value)
    {
        $value->delete(); 

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeAllValues()
    {
        foreach($this->values as $value)
        {
            $value->delete();
        }

        return $this;
    }    

    /**
     * {@inheritdoc}
     */
    public function hasValue(OptionValueInterface $value)
    {
        return $this->values->contains($value);
    }
}
