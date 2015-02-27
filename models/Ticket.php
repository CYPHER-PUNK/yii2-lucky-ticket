<?php
/**
 * @author Ruslan Fadeev
 * created: 19.02.2015 16:08
 */

namespace app\models;

use yii\base\Model;
use app\components\IsEvenValidator;

class Ticket extends Model
{
    /** @var int разрядность билета */
    public $length;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['length'], 'required'],
            [['length'], 'integer', 'min' => 2, 'max' => 50],
            [['length'], IsEvenValidator::className()]
        ];
    }

    public function attributeLabels()
    {
        return ['length' => 'Разрядность (количество цифр)'];
    }

    /**
     * Расчёт списка возможных вариаций номеров счастливых билетов на основе разрядности
     *
     * @param int $iNumber
     * @return int[][]
     */
    private static function numberCount($iNumber)
    {
        $iHalf = (int)($iNumber / 2);
        $aData = [];
        for ($i = 1; $i <= $iHalf; $i++) {
            $iLength = $i * 9 + 1;
            if ($i === 1) {
                for ($j = 0; $j < $iLength; $j++) {
                    $aData[$i][$j] = 1;
                }
            } else {
                $iSum = 0;
                $k = 0;
                for (; $k <= $iLength / 2; $k++) {
                    $iSum += $aData[$i - 1][$k];
                    if ($k >= 10) {
                        $iSum -= $aData[$i - 1][$k - 10];
                    }
                    $aData[$i][$k] = $iSum;
                }
                for (; $k < $iLength; $k++) {
                    $aData[$i][$k] = $aData[$i][$iLength - 1 - $k];
                }
            }
        }
        return $aData;
    }

    /**
     * Быстрый алгоритм расчёта количества счастливых билетов на основе разрядности
     *
     * @param int $iNumber
     * @return int|string
     */
    public static function countLuckyTicket($iNumber)
    {
        $iHalf = (int)($iNumber / 2);
        $aData = self::numberCount($iNumber);
        $iCount = 0;
        for ($i = 1; $i <= $iHalf * 9; $i++) {
            $iCount = function_exists('bcadd') ? bcadd($iCount, bcmul($aData[$iHalf][$i], $aData[$iHalf][$i])) : ($iCount + $aData[$iHalf][$i] * $aData[$iHalf][$i]);
        }
        return $iCount;
    }

    /**
     * Медленный поиск номеров счастливых билетов, зато по порядку
     * @param int $iNumber Разрядность номера
     * @param int $iLimit Количество необходимых номеров
     * @param int $iStart Первый номер, с которого начинать искать счастливые номера
     * @return array
     */
    public static function generateSortedLuckyTicketList($iNumber, $iLimit, $iStart = 1)
    {
        $iHalf = (int)($iNumber / 2);
        $i = 0;
        $aNumbersCount = self::numberCount($iNumber);
        $iDelimiter = pow(10, $iHalf);
        $iLeft = (int)($iStart / $iDelimiter);
        $iRight = $iStart - $iLeft * $iDelimiter;
        $aData = [];
        do { // Цикл по левой части номера
            $iLeftCount = 0;
            $iLeftSum = array_sum(str_split((string)$iLeft));
            do { // Цикл по правой части номера
                $iRightSum = array_sum(str_split((string)$iRight));
                if ($iLeftSum === $iRightSum) {
                    $iValue = $iLeft * $iDelimiter + $iRight;
                    $iLength = strlen((string)$iValue);
                    $aData[] = (string)($iLength === $iNumber ? $iValue : (implode('', array_fill(0, ($iNumber - $iLength), '0')) . $iValue));
                    $i++;
                    $iLeftCount++;
                    // Если количество найденных номеров в правой части для текущей суммы в левой части достигнуто - переходим к следующему числу в левой части
                    if ($iLeftCount >= $aNumbersCount[$iHalf][$iLeftSum]) {
                        break;
                    }
                }
                $iRight++;
            } while ($i < $iLimit && $iRight < $iDelimiter);
            $iRight = 1;
            $iLeft++;
        } while ($i < $iLimit && $iLeft < $iDelimiter);
        return $aData;
    }
}
