<?php
/**
 * Yii2 Columnized.
 *
 * This file contains Columnized widget test.
 *
 * @author  Aleksei Korotin <herr.offizier@gmail.com>
 */

namespace herroffizier\yii2columnized\tests\codeception\unit;

use yii\codeception\TestCase;
use yii\data\ArrayDataProvider;
use herroffizier\yii2columnized\Columnized;

class ColumnWidgetTest extends TestCase
{
    public function dataProviderProvider()
    {
        return [
            [new ArrayDataProvider([
                'allModels' => [
                    ['name' => '[model 1]'],
                    ['name' => '[model 2]'],
                    ['name' => '[model 3]'],
                    ['name' => '[model 4]'],
                ],
            ])],

            [new ArrayDataProvider([
                'allModels' => [
                    ['name' => '[model 1]'],
                    ['name' => '[model 2]'],
                ],
            ])],

            [new ArrayDataProvider([
                'allModels' => [
                    ['name' => '[model 1]'],
                    ['name' => '[model 2]'],
                    ['name' => '[model 3]'],
                    ['name' => '[model 4]'],
                    ['name' => '[model 5]'],
                    ['name' => '[model 6]'],
                ],
            ])],

            [new ArrayDataProvider([
                'allModels' => [
                ],
            ])],

            [new ArrayDataProvider([
                'allModels' => [
                    ['name' => '[model 1]'],
                ],
            ])],

            [new ArrayDataProvider([
                'allModels' => [
                    ['name' => '[model 1]'],
                    ['name' => '[model 2]'],
                    ['name' => '[model 3]'],
                    ['name' => '[model 4]'],
                    ['name' => '[model 5]'],
                ],
            ])],
        ];
    }

    /**
     * Get column count from widget output with container view.
     *
     * @param string $result
     *
     * @return int
     */
    protected function getColumnCount($result)
    {
        $match = null;
        if (!preg_match('/container has (\d+) columns/', $result, $match)) {
            throw new \Exception('Column count is missing');
        }

        return (int) $match[1];
    }

    /**
     * Verify models in widget output.
     *
     * @param string            $result
     * @param ArrayDataProvider $dataProvider
     */
    protected function verifyModels($result, $dataProvider)
    {
        for ($index = 1; $index <= $dataProvider->getTotalCount(); ++$index) {
            $this->assertContains('[model '.$index.']', $result);
        }
    }

    /**
     * @expectedException \yii\base\InvalidConfigException
     */
    public function testEmptyConfig()
    {
        Columnized::widget();
    }

    /**
     * @expectedException \yii\base\InvalidConfigException
     */
    public function testEmptyDataProvider()
    {
        Columnized::widget([
            'itemView' => '@tests/codeception/_data/_item',
        ]);
    }

    /**
     * @expectedException \yii\base\InvalidConfigException
     */
    public function testEmptyItemView()
    {
        Columnized::widget([
            'dataProvider' => new ArrayDataProvider([]),
        ]);
    }

    /**
     * @dataProvider dataProviderProvider
     */
    public function testDefaultConfig($dataProvider)
    {
        $result = Columnized::widget([
            'id' => 'columnized-widget',
            'dataProvider' => $dataProvider,
            'itemView' => '@tests/codeception/_data/_item',
        ]);

        $this->assertContains('columnized-widget', $result);

        $this->verifyModels($result, $dataProvider);
    }

    /**
     * @dataProvider dataProviderProvider
     */
    public function testColumnView($dataProvider)
    {
        $result = Columnized::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '@tests/codeception/_data/_item',
            'columnView' => '@tests/codeception/_data/_column',
        ]);

        $this->verifyModels($result, $dataProvider);
    }

    /**
     * @dataProvider dataProviderProvider
     */
    public function testContainerView($dataProvider)
    {
        $result = Columnized::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '@tests/codeception/_data/_item',
            'containerView' => '@tests/codeception/_data/_container',
        ]);

        $this->assertContains('total '.$dataProvider->getTotalCount().' items', $result);

        $this->verifyModels($result, $dataProvider);
    }

    /**
     * @dataProvider dataProviderProvider
     */
    public function testColumnAndContainerView($dataProvider)
    {
        $result = Columnized::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '@tests/codeception/_data/_item',
            'columnView' => '@tests/codeception/_data/_column',
            'containerView' => '@tests/codeception/_data/_container',
        ]);

        $this->assertContains('total '.$dataProvider->getTotalCount().' items', $result);

        $this->verifyModels($result, $dataProvider);

        $columnCount = $this->getColumnCount($result);
        for ($index = 0; $index < $columnCount; ++$index) {
            $this->assertContains('column '.$index.' has', $result);
        }
    }

    /**
     * @dataProvider dataProviderProvider
     */
    public function testHardcodedColumns($dataProvider)
    {
        $result = Columnized::widget([
            'dataProvider' => $dataProvider,
            'columnSizes' => [1, 1, 1, 1],
            'itemView' => '@tests/codeception/_data/_item',
            'columnView' => '@tests/codeception/_data/_column',
            'containerView' => '@tests/codeception/_data/_container',
        ]);

        $this->assertContains('total '.$dataProvider->getTotalCount().' items', $result);

        $this->verifyModels($result, $dataProvider);

        $columnCount = $this->getColumnCount($result);
        for ($index = 0; $index < $columnCount; ++$index) {
            if ($index + 1 < $columnCount) {
                $this->assertContains('column '.$index.' has 1 items', $result);
            } else {
                $this->assertContains(
                    'column '.$index.' has '.($dataProvider->getTotalCount() - $columnCount + 1).' items',
                    $result
                );
            }
        }
    }
}
