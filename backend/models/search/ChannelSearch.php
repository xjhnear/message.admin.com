<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Channel;
use common\modelsgii\AuthAssignment;

/**
 * AdminSearch represents the model behind the search form about `backend\models\Admin`.
 */
class ChannelSearch extends Channel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userid', 'operator', 'status', 'is_del'], 'integer'],
            [['account', 'password', 'name'], 'string']
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
        $query = Channel::find();

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
            'is_del' => 0,
        ]);

        $query->andFilterWhere([
            'operator' => $this->operator,
        ]);

        $query->andFilterWhere(['like', 'account', $this->account])
            ->andFilterWhere(['like', 'name', $this->name]);
        
        /* 排序 */
        $query->orderBy([
            'channel_id' => SORT_ASC,
        ]);

        return $dataProvider;
    }
}
