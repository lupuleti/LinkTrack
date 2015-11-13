import json
import urllib2
import demjson

searchedLink = "http://freddydumont.startbewijs.be/"
#searchedLink =	"http://www.booloon.com/index/Humbul/Resources/00_Humbul_In_Resources.htm"
#searchedLink =	"http://dig.do/owari.com"
start_url = "http://www.dmoz.org/Sports/Soccer/Statistics"
#start_url = "http://www.dmoz.org/Sports/Soccer/Statistics"
#start_url = "http://zip-fm.co.jp/pro_index/navigator/"
class Queue:
    def __init__(self):
        self.items = []

    def isEmpty(self):
        return self.items == []

    def enqueue(self, item):
        self.items.insert(0,item)

    def dequeue(self):
        return self.items.pop()

    def size(self):
        return len(self.items)

class Tree:
	def __init__(self, _value, _id):
		self.children = []
		self.value = _value
		self.id = _id

	def getChild(self, _id):
		if _id in self.children:
			return self.children[_id]
		else:
			return None

	def setChild(self, _id, _value):
		if _id in self.children:
			self.children[_id]= _value
			return True 
		else:
			return False

	def addChild(self, _child):
		self.children.append(_child)

	def isEmpty(self):
		return None

	def getSize(self):
		return len(self.children)

	def getValue(self):
		return self.value

	def printTree(self, _depth = 0):
		for i in range(0,_depth):
			print "  ",
		print self.id
		for child in self.children: 
			child.printTree(_depth + 1)

	def searchTree(self, _value):
		if self.value is _value:
			return True

	def genGraph(self, G):
		for i in self.children:


			i.genGraph(G)

tree = Tree(start_url, 0)
queue = Queue()

queue.enqueue(tree)
count = 0
iiden = 1


import xmlrpclib
server_url = 'http://127.0.0.1:20738/RPC2'
server = xmlrpclib.Server(server_url)
G = server.ubigraph

G.new_vertex_w_id(0)
G.set_vertex_attribute(0, "color", "#FF0000")

while count < 40:
	print count
	currentTree = queue.dequeue()
	currentVal = currentTree.getValue()
	currentVal.replace(" ", "%20")
	print currentVal
	if not (".php?" in currentVal or ".aspx?" in currentVal or ".asp?" in currentVal or " " in currentVal):
		jsonQ = urllib2.urlopen("http://api.majestic.com/api/json?app_api_key=96CA2AAC8EC2F73FA1365D69BED49B1D&cmd=GetBackLinkData&item=" + currentVal + "&Count=5000&datasource=fresh").read()
		decodedJsonQ = demjson.decode(jsonQ)
		arrayOfDataQ = decodedJsonQ['DataTables']['BackLinks']['Data']
		for item in arrayOfDataQ:
			newTree = Tree(item['SourceURL'], iiden)
			G.new_vertex_w_id(iiden)
			if item['SourceURL'] == searchedLink:
				G.set_vertex_attribute(iiden, "color", "#00FF00")
			G.new_edge(currentTree.id, iiden)
			iiden = iiden + 1
			queue.enqueue(newTree)
			currentTree.addChild(newTree)
		count = count + 1

tree.printTree()
