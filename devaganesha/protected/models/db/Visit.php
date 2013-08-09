<?php

/**
 * This is the model class for table "visits".
 *
 * The followings are the available columns in table 'visits':
 * @property string $node_id
 * @property string $user_id
 * @property string $created
 *
 * The followings are the available model relations:
 * @property Users $user
 * @property Nodes $node
 */
class Visit extends AppActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Visit the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'visits';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('node_id, user_id, created', 'required'),
			array('node_id, user_id', 'length', 'max'=>11),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('node_id, user_id, created', 'safe', 'on'=>'search'),
		);
	}


	
	/**
	 * @return array relational rules.
	 */
	 
	 
	 
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
			'node' => array(self::BELONGS_TO, 'Nodes', 'node_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'node_id' => 'Node',
			'user_id' => 'User',
			'created' => 'Created',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('node_id',$this->node_id,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('created',$this->created,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}