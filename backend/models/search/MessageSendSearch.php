<?php

namespace backend\models\search;

use backend\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\MessageSend;

/**
 * MessageSearch represents the model behind the search form about `backend\models\Message`.
 */
class MessageSendSearch extends MessageSend
{

    public $from_date; // 搜索开始时间
    public $to_date; // 搜索结束时间

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message_id', 'message_did', 'task_id', 'operator', 'channel_id', 'status', 'return_time', 'create_time', 'uid'], 'integer'],
            [['phonenumber', 'errorcode', 'extno'], 'string']
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

        $query = MessageSend::find()->orderBy('message_sid DESC')->asArray();

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

    public function getTodayCount($uid=null)
    {
        $start = mktime(0,0,0,date("m"),date("d"),date("Y"));
        $end = mktime(0,0,0,date("m"),date("d")+1,date("Y"));
        $count_model = MessageSend::find();
        $count_model->andFilterWhere(['between', 'create_time', $start, $end]);
        if ($uid) {
            $count_model->andFilterWhere([
                'uid' => $uid,
            ]);
        }
        $count = $count_model->count();
        return $count;
    }

    public function getTodaySuccessCount($uid=null)
    {
        $start = mktime(0,0,0,date("m"),date("d"),date("Y"));
        $end = mktime(0,0,0,date("m"),date("d")+1,date("Y"));
        $count_model = MessageSend::find();
        $count_model->andFilterWhere(['between', 'create_time', $start, $end]);
        $count_model->andFilterWhere([
            'status' => 10,
        ]);
        if ($uid) {
            $count_model->andFilterWhere([
                'uid' => $uid,
            ]);
        }
        $count = $count_model->count();
        return $count;
    }

    public function getThisMonthCount($uid=null)
    {
        $start = strtotime(date("Y")."-".date("m")."-1");
        $end = strtotime(date("Y")."-".(date("m")+1)."-1");
        $count_model = MessageSend::find();
        $count_model->andFilterWhere(['between', 'create_time', $start, $end]);
        if ($uid) {
            $count_model->andFilterWhere([
                'uid' => $uid,
            ]);
        }
        $count = $count_model->count();
        return $count;
    }

    public function getThisMonthSuccessCount($uid=null)
    {
        $start = strtotime(date("Y")."-".date("m")."-1");
        $end = strtotime(date("Y")."-".(date("m")+1)."-1");
        $count_model = MessageSend::find();
        $count_model->andFilterWhere(['between', 'create_time', $start, $end]);
        $count_model->andFilterWhere([
            'status' => 10,
        ]);
        if ($uid) {
            $count_model->andFilterWhere([
                'uid' => $uid,
            ]);
        }
        $count = $count_model->count();
        return $count;
    }

}
