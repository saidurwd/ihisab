<?php

/**
 * This is the model class for table "{{tag}}".
 *
 * The followings are the available columns in table '{{tag}}':
 * @property string $id
 * @property integer $user
 * @property integer $parent_tag
 * @property string $tag_name
 * @property string $alias
 * @property string $path
 * @property string $created
 * @property string $modified
 */
class Tag extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{tag}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user, tag_name', 'required'),
            array('user, parent_tag', 'numerical', 'integerOnly' => true),
            array('tag_name,alias,path', 'length', 'max' => 150),
            array('created, modified', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, user, parent_tag, tag_name, alias, path, created, modified', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'user' => 'User',
            'parent_tag' => 'Parent',
            'tag_name' => 'Tag',
            'alias' => 'Alias',
            'path' => 'Path',
            'created' => 'Created On',
            'modified' => 'Modofied On',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
        $criteria->alias = 't';
        $criteria->condition = 't.user IN(0,' . Yii::app()->user->id . ') OR t.user IS NULL';

        $criteria->compare('t.id', $this->id, true);
        $criteria->compare('t.user', $this->user);
        $criteria->compare('t.parent_tag', $this->parent_tag);
        $criteria->compare('t.tag_name', $this->tag_name, true);
        $criteria->compare('t.alias', $this->alias, true);
        $criteria->compare('t.path', $this->path, true);
        $criteria->compare('t.created', $this->created, true);
        $criteria->compare('t.modified', $this->modified, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize50'],
            ),
            'sort' => array('defaultOrder' => 't.path'),
        ));
    }

    public function dashboard_report()
    {
        $criteria = new CDbCriteria;
        $criteria->alias = 't';
        $criteria->condition = 't.user IN(0,' . Yii::app()->user->id . ') OR t.user IS NULL';

        $criteria->compare('t.id', $this->id, true);
        $criteria->compare('t.user', $this->user);
        $criteria->compare('t.parent_tag', 0);
        $criteria->compare('t.tag_name', $this->tag_name, true);
        $criteria->compare('t.alias', $this->alias, true);
        $criteria->compare('t.path', $this->path, true);
        $criteria->compare('t.created', $this->created, true);
        $criteria->compare('t.modified', $this->modified, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize50'],
            ),
            'sort' => array('defaultOrder' => 't.tag_name'),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Tag the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public static function get_tag($id)
    {
        $value = Tag::model()->findByAttributes(array('id' => $id));
        if (empty($value->alias)) {
            return null;
        } else {
            return $value->alias;
        }
    }

    public static function get_user($id)
    {
        $value = Tag::model()->findByAttributes(array('id' => $id));
        if (empty($value->user)) {
            return null;
        } else {
            return $value->user;
        }
    }

    public static function get_tags($id)
    {
        $model = Transaction::model()->findByPk($id);
        if (!empty($model->tag)) {
            $exval = explode(',', $model->tag);
            $tags = '';
            $total = count($exval);
            $total_minus = ($total - 1);
            for ($i = 0; $i < $total; $i++) {
                //$tags .= '<span class="label label-warning">' . Tag::get_tag($exval[$i]) . '</span> ';
                $tags .= CHtml::link(Tag::get_tag($exval[$i]), array('transaction/tag', 'id' => $exval[$i]), array('data-placement' => 'bottom', 'title' => Tag::get_tag($exval[$i]), 'rel' => 'tooltip', 'data-original-title' => Tag::get_tag($exval[$i]), 'class' => 'btn btn-xs btn-info')) . ' ';
            }
        } else {
            $tags = null;
        }
        return $tags;
    }

    public static function get_tag_new($model, $field)
    {
        $parent1 = Yii::app()->db->createCommand()
            ->select('id,parent_tag,tag_name,alias')
            ->from('{{tag}}')
            ->where('(parent_tag=0 OR parent_tag IS NULL) AND user IN(0,' . Yii::app()->user->id . ')')
            ->order('path')
            ->queryAll();
        $option = '<select id="' . $model . '_' . $field . '" name="' . $model . '[' . $field . ']" class="select2">';
        $option .= '<option value="">All</option>';
        foreach ($parent1 as $key => $values1) {
            $option .= '<option value="' . $values1["id"] . '" class="text-primary">&Hopf; ' . $values1["alias"] . '</option>';
            $parent2 = Yii::app()->db->createCommand()
                ->select('id,parent_tag,tag_name,alias')
                ->from('{{tag}}')
                ->where('parent_tag=' . $values1["id"] . ' AND user IN(0,' . Yii::app()->user->id . ')')
                ->order('path')
                ->queryAll();
            foreach ($parent2 as $key => $values2) {
                $option .= '<option value="' . $values2["id"] . '" class="text-success">&rAarr; ' . $values2["alias"] . '</option>';
                $parent3 = Yii::app()->db->createCommand()
                    ->select('id,parent_tag,tag_name,alias')
                    ->from('{{tag}}')
                    ->where('parent_tag=' . $values2["id"] . ' AND user IN(0,' . Yii::app()->user->id . ')')
                    ->order('path')
                    ->queryAll();
                foreach ($parent3 as $key => $values3) {
                    $option .= '<option value="' . $values3["id"] . '" class="text-danger">&DoubleRightArrow; ' . $values3["alias"] . '</option>';
                    $parent4 = Yii::app()->db->createCommand()
                        ->select('id,parent_tag,tag_name,alias')
                        ->from('{{tag}}')
                        ->where('parent_tag=' . $values3["id"] . ' AND user IN(0,' . Yii::app()->user->id . ')')
                        ->order('path')
                        ->queryAll();
                    foreach ($parent4 as $key => $values4) {
                        $option .= '<option value="' . $values4["id"] . '" class="text-warning">&srarr; ' . $values4["alias"] . '</option>';
                    }
                }
            }
        }
        $option .= '</select>';

        return $option;
    }

    public static function get_tag_edit($model, $field, $id)
    {
        $parent1 = Yii::app()->db->createCommand()
            ->select('id,parent_tag,tag_name,alias')
            ->from('{{tag}}')
            ->where('(parent_tag=0 OR parent_tag IS NULL) AND user IN(0,' . Yii::app()->user->id . ')')
            ->order('path')
            ->queryAll();
        $option = '<select id="' . $model . '_' . $field . '" name="' . $model . '[' . $field . ']" class="select2">';
        $option .= '<option value="">All</option>';
        foreach ($parent1 as $key => $values1) {
            if ($id == $values1["id"]) {
                $option .= '<option selected="selected" value="' . $values1["id"] . '" class="text-primary">&Hopf; ' . $values1["alias"] . '</option>';
            } else {
                $option .= '<option value="' . $values1["id"] . '" class="text-primary">&Hopf; ' . $values1["alias"] . '</option>';
            }
            $parent2 = Yii::app()->db->createCommand()
                ->select('id,parent_tag,tag_name,alias')
                ->from('{{tag}}')
                ->where('parent_tag=' . $values1["id"] . ' AND user IN(0,' . Yii::app()->user->id . ')')
                ->order('path')
                ->queryAll();
            foreach ($parent2 as $key => $values2) {
                if ($id == $values2["id"]) {
                    $option .= '<option selected="selected"value="' . $values2["id"] . '" class="text-success">&rAarr; ' . $values2["alias"] . '</option>';
                } else {
                    $option .= '<option value="' . $values2["id"] . '" class="text-success">&rAarr; ' . $values2["alias"] . '</option>';
                }
                $parent3 = Yii::app()->db->createCommand()
                    ->select('id,parent_tag,tag_name,alias')
                    ->from('{{tag}}')
                    ->where('parent_tag=' . $values2["id"] . ' AND user IN(0,' . Yii::app()->user->id . ')')
                    ->order('path')
                    ->queryAll();
                foreach ($parent3 as $key => $values3) {
                    if ($id == $values3["id"]) {
                        $option .= '<option selected="selected" value="' . $values3["id"] . '" class="text-danger">&DoubleRightArrow; ' . $values3["alias"] . '</option>';
                    } else {
                        $option .= '<option value="' . $values3["id"] . '" class="text-danger">&DoubleRightArrow; ' . $values3["alias"] . '</option>';
                    }
                    $parent4 = Yii::app()->db->createCommand()
                        ->select('id,parent_tag,tag_name,alias')
                        ->from('{{tag}}')
                        ->where('parent_tag=' . $values3["id"] . ' AND user IN(0,' . Yii::app()->user->id . ')')
                        ->order('path')
                        ->queryAll();
                    foreach ($parent4 as $key => $values4) {
                        if ($id == $values4["id"]) {
                            $option .= '<option selected="selected"value="' . $values4["id"] . '" class="text-warning">&srarr; ' . $values4["alias"] . '</option>';
                        } else {
                            $option .= '<option value="' . $values4["id"] . '" class="text-warning">&srarr; ' . $values4["alias"] . '</option>';
                        }
                    }
                }
            }
        }
        $option .= '</select>';

        return $option;
    }

    public static function checkUser($id)
    {
        if (($model = Tag::model()->find(array('condition' => 'user IN(0,' . Yii::app()->user->id . ') AND id=' . $id))) === null) {
            Yii::app()->user->setFlash('error', 'Illegal access detected. Please don\'t try again!');
            Yii::app()->getController()->redirect(array('/site/index'));
        } else {
            return true;
        }
    }

    //create chart for specific tag page (INCOME) - last 12 years
    public static function income_specific_year_tag($year, $tag)
    {
        $tags = implode(',', Tag::get_parent_child($tag));
        $balance = Yii::app()->db->createCommand()
            ->select('IFNULL(SUM(t.amount),0)')
            ->from('{{transaction}} t')
            ->join('{{transaction_tag}} tt', 't.id=tt.transaction')
            ->where('t.user=' . Yii::app()->user->id . ' AND t.transaction_type IN(2,4) AND YEAR(t.created) = "' . $year . '" AND tt.tag IN(' . $tags . ')')
            ->queryScalar();

        $balance = abs($balance);
        $amount = number_format($balance, 2, '.', '');
        return $amount;
    }

    public static function tagIncomeChartYear($tag)
    {
        $return = null;
        $year = date('Y');
        $from = $year - 12;
        for ($t = $from; $t <= $year; $t++) {
            $return .= Tag::income_specific_year_tag($t, $tag) . ",";
        }

        return $return;
    }

    //create chart for specific tag page (EXPANSE) - last 12 years        
    public static function expense_specific_year_tag($year, $tag)
    {
        $tags = implode(',', Tag::get_parent_child($tag));
        $balance = Yii::app()->db->createCommand()
            ->select('IFNULL(SUM(t.amount),0)')
            ->from('{{transaction}} t')
            ->join('{{transaction_tag}} tt', 't.id=tt.transaction')
            ->where('t.user=' . Yii::app()->user->id . ' AND t.transaction_type IN(1) AND YEAR(t.created) = "' . $year . '" AND tt.tag IN(' . $tags . ')')
            ->queryScalar();

        $balance = abs($balance);
        $amount = number_format($balance, 2, '.', '');
        return $amount;
    }

    public static function tagExpenseChartYear($tag)
    {
        $return = null;
        $year = date('Y');
        $from = $year - 12;
        for ($t = $from; $t <= $year; $t++) {
            $return .= Tag::expense_specific_year_tag($t, $tag) . ",";
        }

        return $return;
    }

    //get total expanse specific tag
    public static function get_expense_tag_total($tag)
    {
        $tags = implode(',', Tag::get_parent_child($tag));
        $balance = Yii::app()->db->createCommand()
            ->select('IFNULL(SUM(t.amount),0)')
            ->from('{{transaction}} t')
            ->join('{{transaction_tag}} tt', 't.id=tt.transaction')
            ->where('t.user=' . Yii::app()->user->id . ' AND t.transaction_type IN(1) AND tt.tag IN(' . $tags . ')')
            ->queryScalar();

        $balance = abs($balance);
        return number_format($balance, 2, '.', '');
    }

    //get total income specific tag
    public static function get_income_tag_total($tag)
    {
        $tags = implode(',', Tag::get_parent_child($tag));
        $balance = Yii::app()->db->createCommand()
            ->select('IFNULL(SUM(t.amount),0)')
            ->from('{{transaction}} t')
            ->join('{{transaction_tag}} tt', 't.id=tt.transaction')
            ->where('t.user=' . Yii::app()->user->id . ' AND t.transaction_type IN(2,4) AND tt.tag IN(' . $tags . ')')
            ->queryScalar();

        $balance = abs($balance);
        return number_format($balance, 2, '.', '');
    }

    public static function getHierarchicalReport($month, $year)
    {
        $user = Yii::app()->user->id;

        $tags = Tag::model()->findAll(array(
            'condition' => 'user IN(0,' . $user . ') OR user IS NULL',
            'order' => 'path, id',
        ));

        if (empty($tags)) {
            return array();
        }

        $tagMap = array();
        foreach ($tags as $tag) {
            $tagMap[$tag->id] = array(
                'id' => $tag->id,
                'tag_name' => $tag->tag_name,
                'alias' => $tag->alias,
                'parent_tag' => $tag->parent_tag,
                'path' => $tag->path,
                'level' => substr_count($tag->path, '.') - 1,
                'expense_direct' => 0,
                'income_direct' => 0,
                'expense_total' => 0,
                'income_total' => 0,
                'children' => array(),
            );
        }

        $expenseRows = Yii::app()->db->createCommand()
            ->select('tt.tag as tag_id, IFNULL(SUM(t.amount),0) as total')
            ->from('{{transaction_tag}} tt')
            ->join('{{transaction}} t', 't.id=tt.transaction')
            ->where('t.user=' . $user . ' AND t.transaction_type IN(1) AND MONTH(t.created)=' . $month . ' AND YEAR(t.created)=' . $year)
            ->group('tt.tag')
            ->queryAll();

        foreach ($expenseRows as $row) {
            if (isset($tagMap[$row['tag_id']])) {
                $tagMap[$row['tag_id']]['expense_direct'] = abs($row['total']);
            }
        }

        $incomeRows = Yii::app()->db->createCommand()
            ->select('tt.tag as tag_id, IFNULL(SUM(t.amount),0) as total')
            ->from('{{transaction_tag}} tt')
            ->join('{{transaction}} t', 't.id=tt.transaction')
            ->where('t.user=' . $user . ' AND t.transaction_type IN(2,4) AND MONTH(t.created)=' . $month . ' AND YEAR(t.created)=' . $year)
            ->group('tt.tag')
            ->queryAll();

        foreach ($incomeRows as $row) {
            if (isset($tagMap[$row['tag_id']])) {
                $tagMap[$row['tag_id']]['income_direct'] = abs($row['total']);
            }
        }

        $roots = array();
        foreach ($tagMap as $id => &$tag) {
            $parent = $tag['parent_tag'];
            if ($parent == 0 || $parent === null || !isset($tagMap[$parent])) {
                $roots[] = &$tag;
            } else {
                $tagMap[$parent]['children'][] = &$tag;
            }
        }
        unset($tag);

        $computeTotals = function (&$node) use (&$computeTotals) {
            $node['expense_total'] = $node['expense_direct'];
            $node['income_total'] = $node['income_direct'];

            foreach ($node['children'] as &$child) {
                $computeTotals($child);
                $node['expense_total'] += $child['expense_total'];
                $node['income_total'] += $child['income_total'];
            }
        };

        foreach ($roots as &$root) {
            $computeTotals($root);
        }
        unset($root);

        $result = array();
        $flatten = function ($nodes) use (&$flatten, &$result) {
            foreach ($nodes as $node) {
                $result[] = $node;
                if (!empty($node['children'])) {
                    $flatten($node['children']);
                }
            }
        };
        $flatten($roots);

        return $result;
    }

    public static function getData($id, $field)
    {
        $value = Tag::model()->findByAttributes(array('id' => $id));
        if (empty($value->$field)) {
            return null;
        } else {
            return $value->$field;
        }
    }

    public static function update_path($id)
    {
        $model = Tag::model()->findByPk($id);
        if ($model->parent_tag == 0 || $model->parent_tag === null) {
            $model->path = '0.' . $model->id;
            $model->save();
        } else {
            $parent = Tag::model()->findByAttributes(array('id' => $model->parent_tag));
            $model->path = $parent->path . '.' . $model->id;
            $model->save();
        }
    }

    public static function update_alias($id)
    {
        $model = Tag::model()->findByPk($id);
        if ($model->parent_tag == 0 || $model->parent_tag === null) {
            $model->alias = $model->tag_name;
            $model->save();
        } else {
            $parent = Tag::model()->findByAttributes(array('id' => $model->parent_tag));
            $model->alias = $parent->alias . '/' . $model->tag_name;
            $model->save();
        }
    }

    public static function get_parent_child($id)
    {
        $tags = array();
        $array_parent = Tag::model()->findAll(array('condition' => 'id=' . $id));
        if (count($array_parent) > 0) {
            foreach ($array_parent as $key => $value) {
                $tags[] = $value["id"];
                $array_child_one = Tag::model()->findAll(array('condition' => 'parent_tag=' . $value["id"]));
                if (count($array_child_one) > 0) {
                    foreach ($array_child_one as $key => $value_one) {
                        $tags[] = $value_one["id"];
                        $array_child_two = Tag::model()->findAll(array('condition' => 'parent_tag=' . $value_one["id"]));
                        if (count($array_child_two) > 0) {
                            foreach ($array_child_two as $key => $value_two) {
                                $tags[] = $value_two["id"];
                                $array_child_three = Tag::model()->findAll(array('condition' => 'parent_tag=' . $value_two["id"]));
                                if (count($array_child_three) > 0) {
                                    foreach ($array_child_three as $key => $value_three) {
                                        $tags[] = $value_three["id"];
                                        $array_child_four = Tag::model()->findAll(array('condition' => 'parent_tag=' . $value_three["id"]));
                                        if (count($array_child_four) > 0) {
                                            foreach ($array_child_four as $key => $value_four) {
                                                $tags[] = $value_four["id"];
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $tags;
    }

}
