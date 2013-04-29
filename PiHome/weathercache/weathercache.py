#!/usr/bin/env python
# Thomas M Clarke. April 2013.
# 
# Takes a single reading from a SensorTag device given the address of the device and the sensor to read.
#
# Inspired by and based on a sciprt by Michael Saunby (mike.saunby.net)
#

import pexpect
import sys
import time
import pyqrcode

# floatfromhex function completely from Michael Saunby sensortag.py scripts
def floatfromhex(h) :
	t = float.fromhex(h)
	if t > float.fromhex('7FFF') :
		t = -(float.fromhex('FFFF') - t)
	return t

def calcTemp(v) :
	return -46.85 + 175.72/65536 * v	

def calcHumidity(v) :
	return -6.0 + 125.0/65536 * v

def readsensor(type) :
	if type == 'pressure' :
		sensor_address_one = '0x4F'
		sensor_address_two = '0x4B'
	else :
		sensor_address_one = '0x3C'
		sensor_address_two = '0x38'

	tool.sendline('char-write-cmd ' + sensor_address_one + ' 01')
	time.sleep(1)
	tool.expect('\[LE\]>')
	tool.sendline('char-read-hnd ' + sensor_address_two)
	tool.expect('descriptor: .*')
	return tool.after.split()
	

bluetooth_address = sys.argv[1]
tool = pexpect.spawn('../bluez-5.2/attrib/gatttool -b ' + bluetooth_address + ' --interactive')
tool.expect('\[LE\]>')
tool.sendline('connect')
tool.expect('\[CON\].*>')

while True:
	try:
		humidity_bytes = readsensor('humidity')
		temperature = calcTemp(floatfromhex(humidity_bytes[2] + humidity_bytes[1]))
		temp = ~0x0003
		t4 = int(floatfromhex(humidity_bytes[4])) & temp
		humidity = calcHumidity(floatfromhex(humidity_bytes[4] + humidity_bytes[3]))
		qr_image = pyqrcode.MakeQRImage('http://www.weatherboxing-theomccaie.rhcloud.com/obs/add?temp=' + str(int(temperature)) + '&humidity=' + str(int(humidity)))
		qr_image.show()
		print 'Temperature: '
		print temperature
		print 'Humidity: '
		print humidity
		time.sleep(10)
	except (KeyboardInterrupt, SystemExit):
		pass
