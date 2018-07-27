<?php

namespace frontend\modules\users\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\users\models\Users;

/**
 * UsersSearch represents the model behind the search form of `frontend\modules\users\models\Users`.
 */
class UsersSearch extends Users
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'role', 'state'], 'integer'],
            [['name', 'pass', 'last_sess', 'email', 'color'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Users::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
             'id' => $this->id,
             'role' => $this->role,
             'state' => $this->state,
        ]);

        $query->andFilterWhere(['like', '.name', $this->name])
            ->andFilterWhere(['like', '.pass', $this->pass])
            ->andFilterWhere(['like', '.last_sess', $this->last_sess])
            ->andFilterWhere(['like', '.email', $this->email])
            ->andFilterWhere(['like', '.color', $this->color]);

        return $dataProvider;
    }
}
