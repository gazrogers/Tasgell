<?php

namespace Model\Entity;

class Tasks extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $title;

    /**
     *
     * @var string
     */
    protected $description;

    /**
     *
     * @var integer
     */
    protected $parentId;

    /**
     * Method to set the value of field title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Method to set the value of field description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Method to set the value of field parentId
     *
     * @param integer $parentId
     * @return $this
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Returns the value of field description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Returns the value of field parentId
     *
     * @return integer
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("tasks");

        $this->hasMany(
            'id',
            'Model\\Entity\\Tasks',
            'parentId',
            [
                'alias' => 'ChildTasks',
                'foreignKey' => true
            ]
        );
        $this->belongsTo(
            'parentId',
            'Model\\Entity\\Tasks',
            'id',
            [
                'alias' => 'ParentTask',
                'foreignKey' => [
                    'allowNulls' => true,
                    'message' => "Specified parent task does not exist"
                ]
            ]
        );
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'tasks';
    }

}
