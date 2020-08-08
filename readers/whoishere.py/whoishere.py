#!/usr/bin/env python

import logging, sys, json, time, signal, httplib, urllib, unicodedata
from scapy.all import *
from netaddr import *

version = "0.2"

filename = "whoishere.conf"
logfilename = "whoishere.log"
maxlenght = 20
minute_list = []
list = []
uniquefingerprint = []

reload(sys)
sys.setdefaultencoding('utf-8')

def ConfigCheck():
	if not os.path.isfile(filename) :
		print "\n\033[91m\033[1m[+]\033[0m No configuration file found.\033[0m\n"
		file = open(filename, "w")
		file.write('{'\
		'"config" : [{"interface": "wlan0mon"},\n'\
		'            {"receiver_host": ""},\n'\
		'            {"receiver_url": "" }],\n'\
		'"list"   : []\n'\
		'}')
		file.close()
		print "\033[93m\033[1m[+]\033[0m Example configuration file created: \033[94m\033[1m[" + filename + "]\033[0m\n"
		print "\033[93m\033[1m[+]\033[0m Modify configuration file to add monitor interface and list of names and MACs.\n"
		print "\033[93m\033[1m[+]\033[0m Then run 'python whoishere.py'\033[0m\n"
		exit()
	else :
		try :
			with open(filename,'rU') as f: list.append(json.load(f))
			global interface
			interface = str(list[0]['config'][0]['interface'])
		except :
			print "\033[91mSomething is wrong with the configuration file."
			print "Edit or delete "+filename+" and try again.\033[0m\n\n"
			exit()

def Welcome() :
	banner = "\n".join([
        '\n        /         /      /                  ',
	'       (___  ___    ___ (___  ___  ___  ___ ',
	'  |   )|   )|   )| |___ |   )|___)|   )|___)',
	'  |/\/ |  / |__/ |  __/ |  / |__  |    |__  '])

        print banner
        print "\n           \033[1;33mWIFI Client Detection %s\033[0m" % version
        print ""
        print "  Author: Pedro Joaquin @_hkm (pjoaquin@websec.mx)"
        print "  To kill this script hit CRTL-C"
        print ""

def PrintConfig() :
	print "\n\033[92m\033[1m[+]\033[0m Current List:"
	print "    # :        MAC        -    NAME"
	for i in range(len(list[0]['list'])) :
		COLOR = '\033[9'+list[0]['list'][i]['color']+'m'
		print "    "+str(i)+" : " + COLOR + list[0]['list'][i]['mac']+ " - " + list[0]['list'][i]['name'] + '\033[0m'
        print "\n\033[92m\033[1m[+]\033[0m Configuration:"
	timea = time.strftime("%Y-%m-%d %H:%M") + "]\033[0m"
	print "    Current Time            \033[94m\033[1m[" + timea
        print "    Configuration File      \033[94m\033[1m[" + filename + "]\033[0m"
	print "    Log File                \033[94m\033[1m[" + logfilename + "]\033[0m"
	print "    Monitor Interface       \033[94m\033[1m[" + interface + "]\033[0m"
	print "\n\033[92m\033[1m[+]\033[0m Listening for probe requests...\n"

def GetOUI(pkt) :
	global oui
	try :
		oui = OUI(pkt.addr2.replace(":","").upper()[0:6])
		oui = oui.registration().org
	except :
		oui = "(Unknown)"

def SearchList(pkt) :
	global COLOR
	global name
	name = "(Unknown)"
        COLOR = ""
	if pkt.info == "" : pkt.info = ""
	for i in range(len(list[0]['list'])) :
		if pkt.addr2 == list[0]['list'][i]['mac'] :
			name = list[0]['list'][i]['name']
			COLOR = '\033[9'+list[0]['list'][i]['color']+'m'

def PrintInfo(pkt) :
	global fingerprint
	timea = time.strftime("%Y-%m-%d %H:%M")
	namef = " NAME: " + name.ljust(maxlenght)[0:maxlenght]
	mac = " MAC: " + pkt.addr2
	SSID = " SSID: " + pkt.info.ljust(maxlenght)[0:maxlenght]
	OUI = " OUI: "+ oui
	db = pkt.dBm_AntSignal
	if db <= -100:
		quality = 0
	elif db >= -50:
		quality = 100
	else:
		quality = 2 * (db + 100)
	quality = str(quality)+"%"
	quality = " SIGNAL: " + quality.ljust(4, ' ')
	fingerprint = COLOR + timea + quality + namef + mac + SSID + OUI +'\033[0m'
	
	if fingerprint not in uniquefingerprint :
		uniquefingerprint.append(fingerprint)
    		print fingerprint
		json_notification(pkt.addr2, pkt.info.ljust(maxlenght)[0:maxlenght], db, 0, 0)

def WriteLog(fingerprint):
        file = open(logfilename, "a")
        file.write(fingerprint + "\n")
        file.close()

def PacketHandler(pkt) :
        if pkt.haslayer(Dot11ProbeReq) :
        	GetOUI(pkt)
		SearchList(pkt)
		PrintInfo(pkt)

def signal_handler(signal, frame):
        print "\n\033[92m\033[1m[+]\033[0m Exiting...\n"
        sys.exit(0)

def json_notification(mac, ssid, dbm, latitude, longitude):
	data = {}
	data['address'] = str(mac)
	data['essid'] = str(ssid)
	data['signal'] = str(dbm)
	data['latitude'] = str(latitude)
	data['longitude'] = str(longitude)

	conn = httplib.HTTPConnection(list[0]['config'][1]['receiver_host'])
	conn.request("POST", list[0]['config'][2]['receiver_url'], json.dumps(data), { "Content-type": "application/x-www-form-urlencoded" })
	conn.getresponse()


Welcome()
ConfigCheck()
PrintConfig()
signal.signal(signal.SIGINT, signal_handler)
sniff(iface=interface, prn = PacketHandler, store=0)
signal.pause()
