<?php


if (!function_exists('formatPrice')) {
    function formatPrice($amount)
    {
        return '₹' . number_format($amount, 2);
    }
}


if (!function_exists('loginShiprocket')) {
    function loginShiprocket()
    {
        $url = "https://apiv2.shiprocket.in/v1/external/auth/login";

                $data = [
                    "email" => "drvenkyspetclinic@gmail.com", // Replace with your email
                    "password" => "drvenky@123" // Replace with your password
                ];

                $headers = [
                    "Content-Type: application/json"
                ];

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $response = curl_exec($ch);
                curl_close($ch);


                $responseData = json_decode($response, true);

                // Return only the token
                return $responseData['token'] ?? null;
                        


    }
}
