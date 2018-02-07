<?php

namespace backend\models\search;

use backend\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Account;

/**
 * AccountSearch represents the model behind the search form about `backend\models\Account`.
 */
class AccountSearch extends Account
{

    public $from_date; // 搜索开始时间
    public $to_date; // 搜索结束时间

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['recharge_count', 'create_time', 'consume_count', 'fail_count'], 'integer'],
            [['c_date'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        //$params = $params ? : Yii::$app->request->getQueryParams();
        
        $query = Account::find()->orderBy('account_id DESC')->asArray();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        /* 基本搜索 */
        $query->andFilterWhere([
            'c_date' => $this->c_date,
        ]);

        /* 时间搜索 */
        if(isset($params['AccountSearch']['from_date']) && isset($params['AccountSearch']['to_date'])){
            $this->from_date = $params['AccountSearch']['from_date'];
            $this->to_date = $params['AccountSearch']['to_date'];
        }
        if($this->from_date !='' && $this->to_date != '') {
            $query->andFilterWhere(['between', 'create_time', strtotime($this->from_date), strtotime($this->to_date)]);
        }
        
        /* 排序 */
        $query->orderBy([
            'account_id' => SORT_DESC,
        ]);

        return $dataProvider;
    }
}
