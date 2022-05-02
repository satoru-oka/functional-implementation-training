<?php

namespace App\Controller;

use Cake\Event\Event;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

/**
 * Answers Controller
 */
class AnswersController extends AppController
{
    const ANSWER_UPPER_LIMIT = 100;

    /**
     * @inheritdoc
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->request->allowMethod(['post']);
    }

    /**
     * バリデーションルールの定義
     * 
     * @param Cake\Validation\Validator $validator バリデーションインスタンス
     * @return Cake\Validation\Validator バリデーションインスタンス
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->nonNegativeInteger('id', 'IDが不正です')
            ->allowEmpty('id', 'create', 'IDが不正です');

        $validator
            ->scalar('body', '回答内容が不正です')
            ->requirePresence('body', 'create', '回答内容が不正です')
            ->notEmpty('body', '回答内容は必ず入力して下さい')
            ->maxLength('body', 140, '回答内容は140字以内で入力して下さい');

        return $validator;
    }

    /**
     * ルールチェッカーを作成
     * 
     * @param \Cake\ORM\RulesChecker $rules ルールチェッカーのオブジェクト
     * @return \Cake\ORM\RulesChecker ルールチェッカーのオブジェクト
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(
            ['question_id'], 'Questions', '質問が既に削除されている為回答することが出来ません'
        ));

        return $rules;
    }
    /**
     * 回答投稿処理
     * 
     * @return \Cake\Http\Response|null 回答投稿後に質問詳細画面に遷移する
     */
    public function add()
    {
        $answer = $this->Answers->newEntity($this->request->getData());
        $count = $this->Answers
            ->find()
            ->where(['question_id' => $answer->question_id])
            ->count();

        if ($count >= self::ANSWER_UPPER_LIMIT) {
            $this->Flash->error('回答の上限数に達しました');

            return $this->redirect(['controller' => 'Questions', 'action' => 'view', $answer->question_id]);
        }
        // TODO:ユーザー管理機能実装時に修正する
        $answer->user_id =1;
        if ($this->Answers->save($answer)) {
            $this->Flash->success('回答を投稿しました');
        } else {
            $this->Flash->error('回答の投稿に失敗しました');
        }

        return $this->redirect(['controller' => 'Questions', 'action' => 'view', $answer->question_id]);
    }

    /**
     * 回答削除処理
     * 
     * @param int $id 回答ID
     * @return \Cake\Htpp\Response|null 回答削除後に質問詳細画面へ遷移する
     */
    public function delete(int $id)
    {
        $answer = $this->Answers->get($id);
        $questionId = $answer->question_id;
        // TODO:回答を削除出来るのは回答投稿者のみとする

        if ($this->Answers->delete($answer)) {
            $this->Flash->success('回答を削除しました');
        } else {
            $this->Flash->error('回答の削除に失敗しました');
        }

        return $this->redirect(['controller' => 'Questions', 'action' => 'view', $questionId]);
    }
}