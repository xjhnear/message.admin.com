<?php

namespace backend\models\search;

use backend\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Message;

/**
 * MessageSearch represents the model behind the search form about `backend\models\Message`.
 */
class MessageCheckSearch extends Message
{

    public $from_date; // 搜索开始时间
    public $to_date; // 搜索结束时间

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_time', 'check_time', 'status', 'count', 'create_uid', 'check_uid'], 'integer'],
            [['content', 'message_code', 'create_name', 'check_name'], 'string']
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

        $query = Message::find()->orderBy('message_id DESC')->asArray();

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
            'status' => $this->status,
        ]);

        /* 商品名 */
        $query->andFilterWhere([
            'like', 'message_code', $this->message_code,
        ]);

        /* 时间搜索 */
        if(isset($params['MessageSearch']['from_date']) && isset($params['MessageSearch']['to_date'])){
            $this->from_date = $params['MessageSearch']['from_date'];
            $this->to_date = $params['MessageSearch']['to_date'];
        }
        if($this->from_date !='' && $this->to_date != '') {
            $query->andFilterWhere(['between', 'create_time', strtotime($this->from_date), strtotime($this->to_date)]);
        }

        /* 排序 */
        $query->orderBy([
            'message_id' => SORT_DESC,
        ]);

        return $dataProvider;
    }
}
