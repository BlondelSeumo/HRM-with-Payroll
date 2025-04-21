<?php
namespace app\Khalti;

class Khalti {

	/**
	 * Verify Payment
	 *
	 * @param string $secret your khalti merchant secret key
	 * @param string $token your khalti api payment transaction token
	 * @param int $amount khalti payment transaction amount
	 * @return array payment details with status
	 */
	public function verifyPayment($secret, $token, $amount) {

		$config = http_build_query(array(
		    'token' => $token,
		    'amount'  => $amount,
		));

		$url = "https://khalti.com/api/v2/payment/verify/";

		# Make the call using API.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$config);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);


		$headers = ['Authorization: Key '.$secret];
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		// Response
		$response = curl_exec($ch);
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		$response = json_encode(array('status_code'=>$status_code,'data'=>json_decode($response)));
		return json_decode($response, true);
	}

	/**
	 * List All The Transactions
	 *
	 * @param string $secret your khalti merchant secret key
	 * @return array all the transactions of the merchant
	 */
	public function listTransactions($secret) {
		$url = "https://khalti.com/api/v2/merchant-transaction/";

		# Make the call using API.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$headers = ['Authorization: Key '.$secret];
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		// Response
		$response = curl_exec($ch);
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return json_decode($response,true);
	}

	/**
	 * Verify Payment
	 *
	 * @param string $secret your khalti merchant secret key
	 * @param string $idx your khalti api payment transaction idx
	 * @return array transaction detail
	 */
	public function getTransaction($secret, $idx) {

		$url = "https://khalti.com/api/v2/merchant-transaction/".$idx."/";

		# Make the call using API.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$headers = ['Authorization: Key '.$secret];
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		// Response
		$response = curl_exec($ch);
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return json_decode($response, true);
	}

	/**
	 * Transaction Status
	 *
	 * @param string $secret your khalti merchant secret key
	 * @param string $token your khalti api payment transaction token
	 * @param int $amount khalti payment transaction amount
	 * @return array transaction status
	 */
	public function transactionStatus($secret,$token,$amount) {
		$config = http_build_query(array(
		    'token' => $token,
		    'amount'  => $amount,
		));

		$url = "https://khalti.com/api/v2/payment/status/";

		# Make the call using API.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url.'?'.$config);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);


		$headers = ['Authorization: Key '.$secret];
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		// Response
		$response = curl_exec($ch);
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return json_decode($response, true);
	}
}
