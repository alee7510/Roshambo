#include <ArduinoHttpClient.h>

#include <WiFiNINA.h>
#include <Servo.h>

#define sensorPin A5

#include "arduino_secrets.h"
char ssid[] = SECRET_SSID;        // your network SSID (name)
char pass[] = SECRET_PASS;    // your network password (use for WPA, or use as key for WEP)

const int FLEX_PIN = A0;
const int FLEX_PIN2 = A1;// Pin connected to voltage divider output

// Measure the voltage at 5V and the actual resistance of your
// 47k resistor, and enter them below:
const float VCC = 4.98; // Measured voltage of Ardunio 5V line
const float R_DIV = 47500.0; // Measured resistance of 3.3k resistor

// Upload the code, then try to adjust these values to more
// accurately calculate bend degree.
const float STRAIGHT_RESISTANCE = 37300.0; // resistance when straight
const float BEND_RESISTANCE = 90000.0; // resistance at 90 deg




int status = WL_IDLE_STATUS;

char server[] = "10.0.0.29";
int port = 80;//3000;

String postData;
String postVariable = "player=2&hand=";

WiFiClient client;
WebSocketClient wsc = WebSocketClient(client, serverAddress, port);

Servo servo_3;
Servo servo_6;
Servo servo_9;

void setup() {

  pinMode(FLEX_PIN, INPUT);
  pinMode(FLEX_PIN2, INPUT);
  
  servo_3.attach(3);
  servo_6.attach(6);
  servo_9.attach(9);
  Serial.begin(9600);

  while (status != WL_CONNECTED) {
    Serial.print("Attempting to connect to Network named: ");
    Serial.println(ssid);
    status = WiFi.begin(ssid, pass);
    delay(10000);
  }

  Serial.print("SSID: ");
  Serial.println(WiFi.SSID());
  IPAddress ip = WiFi.localIP();
  IPAddress gateway = WiFi.gatewayIP();
  Serial.print("IP Address: ");
  Serial.println(ip);
}

void loop() {
  wsc.begin();
  int hand;
  int flexADC = analogRead(FLEX_PIN);
  float flexV = flexADC * VCC / 1023.0;
  float flexR = R_DIV * (VCC / flexV - 1.0);
  Serial.println("Resistance: " + String(flexR) + " ohms");

  // Use the calculated resistance to estimate the sensor's
  // bend angle:
  float angle = map(flexR, STRAIGHT_RESISTANCE, BEND_RESISTANCE,
                   0, 90.0);
  Serial.println("Bend: " + String(angle) + " degrees");
  int flexADC2 = analogRead(FLEX_PIN2);
  float flexV2 = flexADC2 * VCC / 1023.0;
  float flexR2 = R_DIV * (VCC / flexV2 - 1.0);
  Serial.println("Resistance2: " + String(flexR2) + " ohms");

  // Use the calculated resistance to estimate the sensor's
  // bend angle:
  float angle2 = map(flexR2, STRAIGHT_RESISTANCE, BEND_RESISTANCE,
                   0, 90.0);
  Serial.println("Bend2: " + String(angle2) + " degrees");
  
  if (angle2 >0 && angle >0) // rock
  {
      hand = 1;
      Serial.println("rock");
      servo_3.write(179);
      delay(1000);
      servo_3.write(90);
      delay(500);
  } 
  else if (angle2 <0 && angle <0) // paper
  {
      hand = 2;
      Serial.println("paper");
      servo_6.write(179);
      delay(1000);
      servo_6.write(90);
      delay(500);
  }

  else if (angle2 >0 && angle <0) // scissors
  {
      hand = 3;
      Serial.println("scissors");
      servo_9.write(179);
      delay(1000);
      servo_9.write(90);
      delay(500);
      
  }
  
  postData = postVariable + hand;
//  if (client.connect(server, 80)) {
//    client.println("POST /roshambo/api.php HTTP/1.1");
//    client.println("Host: 10.0.0.29");
//    client.println("Content-Type: application/x-www-form-urlencoded");
//    client.print("Content-Length: ");
//    client.println(postData.length());
//    client.println();
//    client.print(postData);
//  }
//
//  if (client.connected()) {
//    client.stop();
//  }
  Serial.println(postData);

  delay(5000);
}
