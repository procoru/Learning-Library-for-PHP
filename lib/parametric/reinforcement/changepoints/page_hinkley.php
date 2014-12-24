<?php
	// Page-Hinkley test (found most predominantly in Mouss et al. 2004). $alpha represents the minimum detected amplitude and $lambda equals the probabilistic false alarm rate. According to Ikonomovska (2012), $alpha should be proportional to the true standard deviation of the data, and experimental results show that setting it equal to 0.1 * \sigma works out ideally. Returns a single true or false for whether we're detecting a changepoint or not.
	function PageHinkley($list, $alpha, $lambda) {
		return _PageHinkleyStatistic($list, count($list), $alpha) > $lambda;
	}

	function _PageHinkleyStatistic($list, $t, $alpha, $mult=1) {
		$used_list = array_slice($list, 0, $t);
		$mean = ll_mean($used_list);

		$sum = 0;
		$min = -INF;
		for($i = 0; $i < $t; $i++) {
			$sum += ($used_list[$i] - $mean - $alpha) * $mult;
		}
		return $sum - $min;
	};

	
	// Ikonomovska (2012)'s improvement to the P-H test, letting $alpha be an online representation of the standard deviation.
	function improvedPageHinkley($list, $lambda) {
		$alphaPH = function($t) {
			$used_list = array_slice($list, 0, $t);
			return ll_variance($used_list);
		};

		$mean = ll_mean($used_list);
		return _PageHinkleyStatistic($list, count($list), ll_sign($list[count($list)-1] - $mean), $alphaPH(count($list))/2);
	} 
