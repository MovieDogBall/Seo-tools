<?php

/* @var $this yii\web\View */
use yii\widgets\ActiveForm;

$this->title = 'My Yii Application';
?>
<div class="site-index">
    <div class="tabs">
        <div class="row">
            <ul id="ulTab">
                <li>
                    <div class="col-sm-2 col-sm-offset-2">
                        <input type="button" class="btn btn-default btn-lg btn-block" value="Keywords Cleaner"></div>
                </li>
                <li>
                    <div class="col-sm-2 col-sm-offset-1">
                        <input type="button" class="btn btn-default btn-lg btn-block" value="Text Analyzer">
                    </div>
                </li>
                <li>
                    <div class="col-sm-2 col-sm-offset-2">
                        <input type="button" class="btn btn-default btn-lg btn-block" value="Medians">
                    </div>
                </li>
            </ul>
        </div>
        <div id="tab-0">
            <div class="row">
                <div class="col-sm-3 col-sm-offset-2">
                    <?php
                    $form = ActiveForm::begin([
                        'id' => 'theForm',
                        'method' => 'post',
                        'enableAjaxValidation' => false
                    ]) ?>
                    <div class="row">
                        <?= $form->field($model, 'phrases')
                            ->textarea(['id' => 'phrases', 'name' => 'phrases', 'class' => 'form-control', 'rows' => '5'])
                            ->label('Input keywords phrases', ['style' => 'margin-bottom: 15px;']) ?>
                    </div>
                    <div class="row">
                        <div class="col-sm-10">
                            <?= $form->field($model, 'length')
                                ->input('number', ['id' => 'length', 'name' => 'length', 'value' => '1', 'class' => 'form-control', 'min' => '1', 'max' => '30'])
                                ->label('Minimum word length:', ['class' => 'control-label col-sm-9']) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-10">
                            <?= $form->field($model, 'count')
                                ->input('number', ['id' => 'count', 'name' => 'count', 'class' => 'form-control', 'value' => '1', 'min' => '1'])
                                ->label('Minimum numbers of entries:', ['class' => 'control-label col-sm-9']) ?>
                        </div>
                    </div>
                    <div class="row">
                        <?= $form->field($model, 'negative')
                            ->textarea(['id' => 'negative', 'name' => 'negative', 'class' => 'form-control'])
                            ->label('Negative words', ['style' => 'margin-bottom: 15px;']) ?>
                    </div>
                    <div class="row">
                        <?= $form->field($model, 'reset')
                            ->input('reset', ['value' => 'Clear all info', 'class' => 'col-sm-3 btn btn-default btn-md'])
                            ->label(false) ?>
                        <?= $form->field($model, 'submit')
                            ->input('button', ['id' => 'submit', 'value' => 'Calculate', 'class' => 'col-sm-3 col-sm-offset-6 btn btn-primary btn-md', 'onclick' => 'ajaxKeyword()'])
                            ->label(false) ?>
                    </div>
                    <?php ActiveForm::end() ?>
                </div>
                <input type="button" value="Copy to Text Analyzer" onclick="copyAnalyzer()"
                       class="col-sm-1 btn btn-default btn-md col-md-offset-1">

                <div class="col-sm-5 col-md-offset-1" style="padding: 0;">
                    <div class="table-responsive">
                        <table id="table-result" class="display table table-bordered">
                            <thead>
                            <tr>
                                <th>
                                    <p>Keywords</p>
                                </th>
                                <th>
                                    <p>Entries</p>
                                </th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

            </div><!-- /.row -->
        </div><!-- /.row -->

        <div id="tab-1">
            <div class="row">
                <div class="col-sm-2 col-sm-offset-2">
                    <?php
                    $model = new \app\models\KeywordAnalyzer();
                    $form = ActiveForm::begin([
                        'id' => 'analyzerForm',
                        'method' => 'post',
                    ]) ?>
                    <div class="row">
                        <?= $form->field($model, 'sentences')
                            ->textarea(['id' => 'sentences', 'name' => 'sentences', 'class' => 'form-control', 'rows' => '5'])
                            ->label('Input sentences', ['style' => 'margin-bottom: 15px;']) ?>
                    </div>
                    <div class="row">
                        <?= $form->field($model, 'keywordWords')
                            ->textarea(['id' => 'keyword-words', 'name' => 'keywordWords', 'class' => 'form-control', 'rows' => '5'])
                            ->label('Input keyword phrases', ['style' => 'margin-bottom: 15px;']) ?>
                    </div>
                    <div class="row">
                        <?= $form->field($model, 'reset')
                            ->input('reset', ['value' => 'Clear all', 'class' => 'col-sm-3 btn btn-default btn-md'])
                            ->label(false) ?>
                        <?= $form->field($model, 'submit')
                            ->input('button', ['id' => 'submit', 'value' => 'Calculate', 'class' => 'col-sm-3 col-sm-offset-6 btn btn-primary btn-md', 'onclick' => 'ajaxAnalyzer()'])
                            ->label(false) ?>
                    </div>
                    <?php ActiveForm::end() ?>
                </div>

                <div class="col-sm-2 col-md-offset-1" style="padding: 0;">
                    <div class="table-responsive">
                        <table id="table-found-words" class="display table table-bordered">
                            <thead>
                            <tr>
                                <th>
                                    <p>Found Words</p>
                                </th>
                                <th>
                                    <p>Entries</p>
                                </th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <table id="table-not-found-words" class="display table table-bordered">
                            <thead>
                            <tr>
                                <th>
                                    <p>Not Found</p>
                                </th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="col-sm-2 col-md-offset-1" style="padding: 0;">
                    <div class="text-info">
                        <p></p>
                    </div>
                </div>
            </div><!-- /.row -->
            <div class="row">
                <div class="col-sm-3 col-md-offset-2" style="padding: 0;">
                    <div class="table-responsive">
                        <table id="table-analyze-sentences" class="display table table-bordered">
                            <thead>
                            <tr>
                                <th>
                                    <p># Sentence</p>
                                </th>
                                <th>
                                    <p>Count words</p>
                                </th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div id="myCanvas" class="col-sm-5 col-md-offset-1">
                    <canvas id="canvas"></canvas>
                </div>
            </div>
        </div><!-- /.row -->

        <div id="tab-2">
            <div class="row">
                <div class="col-sm-2 col-sm-offset-2">
                    <?php
                    $model = new \app\models\KeywordMedians();
                    $form = ActiveForm::begin([
                        'id' => 'medianForm',
                        'method' => 'post',
                    ]) ?>
                    <div class="row">
                        <?= $form->field($model, 'ourArticle')
                            ->textarea(['id' => 'our-article', 'name' => 'our-article', 'class' => 'form-control', 'rows' => '5'])
                            ->label('Input our article', ['style' => 'margin-bottom: 15px;']) ?>
                    </div>
                    <div class="row">
                        <?= $form->field($model, 'keywordMedian')
                            ->textarea(['id' => 'keyword-median', 'name' => 'keyword-median', 'class' => 'form-control', 'rows' => '5'])
                            ->label('Input keyword', ['style' => 'margin-bottom: 15px;']) ?>
                    </div>
                    <div class="row">
                        <?= $form->field($model, 'reset')
                            ->input('reset', ['value' => 'Clear all', 'class' => 'col-sm-3 btn btn-default btn-md'])
                            ->label(false) ?>
                        <?= $form->field($model, 'submit')
                            ->input('button', ['id' => 'submit', 'value' => 'Calculate', 'class' => 'col-sm-3 col-sm-offset-6 btn btn-primary btn-md', 'onclick' => 'ajaxMedian()'])
                            ->label(false) ?>
                    </div>
                </div>

                <div class="col-sm-2 col-md-offset-1" style="padding: 0;">
                    <div class="row">
                        <?= $form->field($model, 'articles')
                            ->textarea(['id' => 'article-1', 'name' => 'article-1', 'class' => 'form-control', 'rows' => '5'])
                            ->label('Input 1-st article', ['style' => 'margin-bottom: 15px;']) ?>
                    </div>
                    <div class="row">
                        <?= $form->field($model, 'articles')
                            ->textarea(['id' => 'article-2', 'name' => 'article-2', 'class' => 'form-control', 'rows' => '5'])
                            ->label('Input 2-st article', ['style' => 'margin-bottom: 15px;']) ?>
                    </div>
                    <div class="row">
                        <?= $form->field($model, 'articles')
                            ->textarea(['id' => 'article-3', 'name' => 'article-3', 'class' => 'form-control', 'rows' => '5'])
                            ->label('Input 3-st article', ['style' => 'margin-bottom: 15px;']) ?>
                    </div>
                    <div class="row">
                        <?= $form->field($model, 'articles')
                            ->textarea(['id' => 'article-4', 'name' => 'article-4', 'class' => 'form-control', 'rows' => '5'])
                            ->label('Input 4-st article', ['style' => 'margin-bottom: 15px;']) ?>
                    </div>
                    <div class="row">
                        <?= $form->field($model, 'articles')
                            ->textarea(['id' => 'article-5', 'name' => 'article-5', 'class' => 'form-control', 'rows' => '5'])
                            ->label('Input 5-st article', ['style' => 'margin-bottom: 15px;']) ?>
                    </div>
                </div>
                <div class="col-sm-2 col-md-offset-1" style="padding: 0;">
                    <div class="row">
                        <?= $form->field($model, 'articles')
                            ->textarea(['id' => 'article-6', 'name' => 'article-1', 'class' => 'form-control', 'rows' => '5'])
                            ->label('Input 6-st article', ['style' => 'margin-bottom: 15px;']) ?>
                    </div>
                    <div class="row">
                        <?= $form->field($model, 'articles')
                            ->textarea(['id' => 'article-7', 'name' => 'article-2', 'class' => 'form-control', 'rows' => '5'])
                            ->label('Input 7-st article', ['style' => 'margin-bottom: 15px;']) ?>
                    </div>
                    <div class="row">
                        <?= $form->field($model, 'articles')
                            ->textarea(['id' => 'article-8', 'name' => 'article-3', 'class' => 'form-control', 'rows' => '5'])
                            ->label('Input 8-st article', ['style' => 'margin-bottom: 15px;']) ?>
                    </div>
                    <div class="row">
                        <?= $form->field($model, 'articles')
                            ->textarea(['id' => 'article-9', 'name' => 'article-4', 'class' => 'form-control', 'rows' => '5'])
                            ->label('Input 9-st article', ['style' => 'margin-bottom: 15px;']) ?>
                    </div>
                    <div class="row">
                        <?= $form->field($model, 'articles')
                            ->textarea(['id' => 'article-10', 'name' => 'article-5', 'class' => 'form-control', 'rows' => '5'])
                            ->label('Input 10-st article', ['style' => 'margin-bottom: 15px;']) ?>
                    </div>
                </div>
                <?php ActiveForm::end() ?>
            </div><!-- /.row -->
            <div class="row">
                <div class="" style="padding: 0;">
                    <div class="table-responsive">
                        <table id="table-median" class="display table table-bordered">
                            <thead>

                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div id="medianCanvas" class="col-sm-5 col-md-offset-1">
                    <canvas id="canvasMed"></canvas>
                </div>
            </div>
        </div><!-- /.row -->
    </div>
</div>

