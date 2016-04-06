# Yii2 Columnized

[![Build Status](https://travis-ci.org/herroffizier/yii2-columnized.svg?branch=develop)](https://travis-ci.org/herroffizier/yii2-columnized) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/herroffizier/yii2-columnized/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/herroffizier/yii2-columnized/?branch=develop) [![Code Coverage](https://scrutinizer-ci.com/g/herroffizier/yii2-columnized/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/herroffizier/yii2-columnized/?branch=develop)

Yii2 Columnized is a widget that reprensents data provider models in column format.

## Installation

Install extension with Composer:

```bash
composer require "herroffizier/yii2-columnized:@stable"
```

## Usage

```php
echo \herroffizier\yii2columnized\Columnized::widget([
    // data provider (ensure that pagination is disabled!)
    'dataProvider' => $dataProvider,
    // column count
    'columns' => 4,
    // item view
    'itemView' => '@app/views/common/_item',
]);
```

Refer to source code for more options.