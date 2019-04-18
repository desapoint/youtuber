import requests, os, pafy, re, sys
from urlparse import urlparse
from bs4 import BeautifulSoup
from urllib2 import quote

def removePercent(string):

	while string.find("%25")!= -1:
		string=string.replace("%25","%")

	return string
def simplify(string):
	if string == "":
		return string

	string = string.replace('"',"")
	string = string.replace("'","")
	
	tobe = ["Official", "Music", "Video", "Original", "Audio", "AUDIO", "Lyric", "OUT", "NOW"]
	for phrase in tobe:
		string = string.replace(phrase, "")

	string = re.sub(r'\([^)]*\)', '', string)
	string = re.sub(r'\[[^)]*\]', '', string)

	string = string.strip()

	return string
def extractFeatandRemix(string):

	feats = ["feat", " ft ", "ft.", "Ft.", "Feat.", "with"]
	endings = ["(", ")", "[", "]", "-", '"', "'"]

	featString = "none"
	featTypeR = "none"
	remixString = "none"

	for featType in feats:

		indexFound = string.find(featType)

		if indexFound != -1:

			i = indexFound
			while i < len(string):
				if string[i] in endings:
					break
				i = i + 1

			featString = string[indexFound : i].strip()
			featTypeR = featType	
			
	indexFound = string.find("Remix")

	if indexFound != -1:
		i = indexFound
		while i > 0:
			if string[i] in endings:
				break
			i = i - 1

			remixString = "(" + string[i + 1: indexFound + len("Remix")].strip() + ")"

	return (featString, featTypeR, remixString)
def getNames(string):

	(featString, featTypeR, remixString) = extractFeatandRemix(string)
	string = simplify(string)

	if "-" in string:
		dashIndex = string.find("-")
		artist = string[0 : dashIndex].strip()

		if featString == "none":
			song = string[dashIndex + 1 : len(string)].strip()
		else:

			if string.find(featTypeR) != -1:

				if dashIndex < string.find(featTypeR):
					song = string[dashIndex + 1 : string.find(featTypeR)]
					song = song.strip() +" "+ featString
				else:
					song = string[dashIndex + 1 : len(string)].strip()
					
			else:
				song = string[dashIndex + 1 : len(string)].strip() +" "+ featString

		if remixString != "none":
			song = song +" "+ remixString
		
		return (song, artist)

	else:
		if remixString == "none":
			return (string,"")
		else:
			return (string +" "+ remixString,"")

# read the URL sent by PHP
vidTitle = sys.argv[1].strip()

title = unicode(vidTitle)
songName, artistName = getNames(title)

print songName.encode('utf-8')
print artistName.encode('utf-8')
