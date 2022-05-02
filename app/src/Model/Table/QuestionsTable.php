<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Questions Model
 */
class QuestionsTable extends Table
{
    /**
     * @inheritdoc
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('questions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Answers', [
            'foreignKey' => 'question_id'
        ]);
    }

    /**
     * バリデーションルールの定義
     * @param \Cake\Validation\Validator $validator バリデーションインスタンス
     * @return \Cake\Valication\Validator バリデーションインスタンス
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->nonNegativeInteger('id', 'IDが不正です')
            ->allowEmpty('id', 'create', 'IDが不正です');

        $validator
            ->scalar('body', '質問内容が不正です')
            ->requirePresence('body', 'create', '質問内容が不正です')
            ->notEmpty('body', '質問内容は必ず入力して下さい')
            ->maxLength('body', 140, '質問内容は140文字以内で入力して下さい');

        return $validator;
    }

    /**
     * 回答付きの質問一覧を取得する
     * 
     * @return \Cake\ORM\Query 回答付きの質問一覧クエリ
     */
    public function findQuestionsWithAnsweredCount()
    {
        $query = $this->find();
        $query
            ->select(['answered_count' => $query->func()->count('Answers.id')])
            ->leftJoinWith('Answers')
            ->group(['Questions.id'])
            ->enableAutoFields(true);

        return $query;
    }
}