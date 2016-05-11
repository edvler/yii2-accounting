<?php

namespace edvlerblog\accounting\models\db;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use edvlerblog\accounting\models\db\Account;

/**
 * AccountSearch represents the model behind the search form about `edvlerblog\accounting\models\db\Account`.
 */
class AccountSearch extends Account
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_id'], 'integer'],
            [['accounttype', 'name'], 'safe'],
            [['balance_debit', 'balance_credit', 'balance'], 'number'],
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
        $query = Account::find();

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
            'account_id' => $this->account_id,
            'balance_debit' => $this->balance_debit,
            'balance_credit' => $this->balance_credit,
            'balance' => $this->balance,
        ]);

        $query->andFilterWhere(['like', 'accounttype', $this->accounttype])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
