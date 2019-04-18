import eyed3, requests, subprocess, os, sys, datetime

audioURL = sys.argv[1]
data = sys.argv[2:6]

audiofile = eyed3.load(audioURL) # tagging starts
audiofile.initTag(eyed3.id3.ID3_V2_3)

audiofile.tag.artist = unicode(data[1].strip())		# artist
audiofile.tag.title = unicode(data[0].strip())				# title
audiofile.tag.album = unicode(data[2].strip())				# album
imagedata = requests.get(data[3]).content

imgformat = data[3].split(".")[-1].lower()
if imgformat == "png":
    audiofile.tag.images.set (3, imagedata, "image / png")
else:
    audiofile.tag.images.set (3, imagedata, "image / jpeg")
audiofile.tag.save()
print audioURL
