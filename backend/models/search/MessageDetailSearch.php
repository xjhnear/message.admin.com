<?php

namespace backend\models\search;

use backend\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\MessageDetail;

/**
 * MessageSearch represents the model behind the search form about `backend\models\Message`.
 */
class MessageDetailSearch extends MessageDetail
{

    public $from_date; // 搜索开始时间
    public $to_date; // 搜索结束时间

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message_id', 'send_time', 'return_time', 'status', 'create_uid', 'operator'], 'integer'],
            [['phonenumber', 'message_code', 'content'], 'string']
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

        $query = MessageDetail::find()->orderBy('message_did DESC')->asArray();

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

        $query->andFilterWhere([
            'create_uid' => Yii::$app->user->identity->uid,
        ]);

        /* 基本搜索 */
        $query->andFilterWhere([
            'message_id' => $params['pid'],
        ]);

        $query->andFilterWhere([
            'status' => $this->status,
        ]);

        $query->andFilterWhere([
            'operator' => $this->operator,
        ]);

        /* 商品名 */
        $query->andFilterWhere([
            'like', 'phonenumber', $this->phonenumber,
        ]);

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
            'message_did' => SORT_DESC,
        ]);

        return $dataProvider;
    }

    public function getWaitCount($message_id)
    {
        $count = MessageDetail::find()->andFilterWhere(['message_id' => $message_id,'status' => 5])->count();
        return $count;
    }

    public function getSuccessCount($message_id)
    {
        $count = MessageDetail::find()->andFilterWhere(['message_id' => $message_id,'status' => 3])->count();
        return $count;
    }

    public function getfailCount($message_id)
    {
        $count = MessageDetail::find()->andFilterWhere(['message_id' => $message_id,'status' => 4])->count();
        return $count;
    }

}
