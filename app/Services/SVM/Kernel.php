<?php

namespace App\Services\SVM;

/**
 * Kernel Functions untuk SVM
 *
 * Kernel function digunakan untuk mentransformasi data ke dimensi yang lebih tinggi
 * sehingga data yang tidak linear separable bisa dipisahkan.
 */
class Kernel
{
    /**
     * Linear Kernel: K(x, y) = x · y
     *
     * @param array $x Vector pertama
     * @param array $y Vector kedua
     * @return float
     */
    public static function linear(array $x, array $y): float
    {
        return self::dotProduct($x, $y);
    }

    /**
     * Polynomial Kernel: K(x, y) = (gamma * x · y + coef0)^degree
     *
     * @param array $x Vector pertama
     * @param array $y Vector kedua
     * @param float $gamma Parameter gamma
     * @param float $coef0 Coefficient 0 (biasanya 1)
     * @param int $degree Derajat polynomial
     * @return float
     */
    public static function polynomial(array $x, array $y, float $gamma = 1.0, float $coef0 = 1.0, int $degree = 3): float
    {
        return pow($gamma * self::dotProduct($x, $y) + $coef0, $degree);
    }

    /**
     * RBF (Radial Basis Function) Kernel: K(x, y) = exp(-gamma * ||x - y||^2)
     *
     * Kernel yang paling umum digunakan untuk data non-linear
     *
     * @param array $x Vector pertama
     * @param array $y Vector kedua
     * @param float $gamma Parameter gamma (default: 1/n_features)
     * @return float
     */
    public static function rbf(array $x, array $y, float $gamma = 0.1): float
    {
        $diff = array_map(fn($a, $b) => $a - $b, $x, $y);
        $squaredNorm = self::dotProduct($diff, $diff);
        return exp(-$gamma * $squaredNorm);
    }

    /**
     * Sigmoid Kernel: K(x, y) = tanh(gamma * x · y + coef0)
     *
     * @param array $x Vector pertama
     * @param array $y Vector kedua
     * @param float $gamma Parameter gamma
     * @param float $coef0 Coefficient 0
     * @return float
     */
    public static function sigmoid(array $x, array $y, float $gamma = 0.1, float $coef0 = 0.0): float
    {
        return tanh($gamma * self::dotProduct($x, $y) + $coef0);
    }

    /**
     * Menghitung dot product dari dua vector
     *
     * @param array $x Vector pertama
     * @param array $y Vector kedua
     * @return float
     */
    public static function dotProduct(array $x, array $y): float
    {
        $sum = 0.0;
        $n = min(count($x), count($y));

        for ($i = 0; $i < $n; $i++) {
            $sum += $x[$i] * $y[$i];
        }

        return $sum;
    }
}
