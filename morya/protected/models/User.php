<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property string $id
 * @property string $email
 * @property string $password
 * @property string $name
 * @property integer $contact
 * @property string $ganpati_pic
 * @property string $add_line_1
 * @property string $add_line_2
 * @property string $taluka
 * @property string $city
 * @property string $created
 * @property string $modified
 *
 * The followings are the available model relations:
 * @property Comments[] $comments
 * @property Durvas[] $durvases
 * @property Logins[] $logins
 * @property PhotoHits[] $photoHits
 * @property Photoes[] $photoes
 * @property Temples[] $temples
 * @property Vedics[] $vedics
 */
class User extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
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
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email, password, name, contact, add_line_1, add_line_2, taluka, city, created, modified', 'required'),
			array('contact', 'numerical', 'integerOnly'=>true),
			array('email, password, name, add_line_1, add_line_2', 'length', 'max'=>255),
			array('ganpati_pic', 'length', 'max'=>11),
			array('taluka, city', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, email, password, name, contact, ganpati_pic, add_line_1, add_line_2, taluka, city, created, modified', 'safe', 'on'=>'search'),
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
			'comments' => array(self::MANY_MANY, 'Comments', 'modaks(user_id, comment_id)'),
			'durvases' => array(self::HAS_MANY, 'Durvas', 'user_id'),
			'logins' => array(self::HAS_MANY, 'Logins', 'user_id'),
			'photoHits' => array(self::HAS_MANY, 'PhotoHits', 'user_id'),
			'photoes' => array(self::HAS_MANY, 'Photoes', 'user_id'),
			'temples' => array(self::HAS_MANY, 'Temples', 'user_id'),
			'vedics' => array(self::HAS_MANY, 'Vedics', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'email' => 'Email',
			'password' => 'Password',
			'name' => 'Name',
			'contact' => 'Contact',
			'ganpati_pic' => 'Ganpati Pic',
			'add_line_1' => 'Add Line 1',
			'add_line_2' => 'Add Line 2',
			'taluka' => 'Taluka',
			'city' => 'City',
			'created' => 'Created',
			'modified' => 'Modified',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('contact',$this->contact);
		$criteria->compare('ganpati_pic',$this->ganpati_pic,true);
		$criteria->compare('add_line_1',$this->add_line_1,true);
		$criteria->compare('add_line_2',$this->add_line_2,true);
		$criteria->compare('taluka',$this->taluka,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('modified',$this->modified,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}