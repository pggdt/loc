from android import Android
import sys,time,socket,urllib,http.cookiejar,sqlite3
droid = Android()
geoData={}
ac=''
ps=''

def upload():
  del_ID=[]
  conn = sqlite3.connect('/storage/sdcard0/home/test.db')
  c = conn.cursor()
  c.execute ("""select * from LocalLoc""")
  while 1:
    r=c.fetchone()
    droid.eventPost('insert',str(len(del_ID))+'fetched')
    if r!=None:
      rs=doPost("""$_SESSION["user_id"]""",r[1],r[2],r[3],r[4],'0',r[5],r[6],r[7],r[8],r[9],r[10])
      print(rs)
      rs1='Record at '+r[8]+' added.'
      if len(rs)==len(rs1)+1:
        del_ID.append(r[0])
      else:
      	print('delete id='+str(r[0])+' failed')
    else:
      break
  for i in del_ID:
    c.execute ("""delete from LocalLoc where ID="""+str(i))
  conn.commit()
  droid.eventPost('insert',str(len(del_ID))+'items uploaded')
  c.close()

def setGeoData(location,code):
  global geoData
  timeS= time.localtime(location['time']/1000)
  myreadTime = time.strftime("%Y-%m-%d %H:%M:%S",timeS)
  geoData['readTime']=myreadTime
  geoData['provider']=str(location['provider'])
  geoData['latitude']='{0:.8f}'.format(location['latitude'])
  geoData['longitude']='{0:.10f}'.format(location['longitude'])
  geoData['accuracy']=str(int(location['accuracy']))
  if location['altitude']!=0:
    geoData['altitude']='{0:.4f}'.format(location['altitude'])
  else:
    geoData['altitude']='0'
  if location['speed']!=0:
    geoData['speed']='{0:.4f}'.format(location['speed'])
  else:
    geoData['speed']='0'
  if location['bearing']!=0:
    geoData['heading']='{0:.2f}'.format(location['bearing'])
  else:
    geoData['heading']='0'
  geoData['timestamp']=str(location['time'])
  if code=="ggCode":
    geoData['geoCode']=getgeocode()+' based on '+geoData['provider']
  else:
    geoData['geoCode']='based on '+geoData['provider']

def doPost(userId,latitude,longitude,accuracy,altitude,altitudeAccuracy,heading,speed,locTimestamp,locTime,textNote='',geoCode=''):  
  data = urllib.parse.urlencode({'user_id':userId,'latitude': latitude ,'longitude':longitude,'accuracy':accuracy,'altitude':altitude,'altitudeAccuracy':altitudeAccuracy,'heading':heading,'speed':speed,'timestamp':locTimestamp,'time':locTime,'text':textNote,'geoCode':geoCode})
  data=data.encode('utf-8')
  request = urllib.request.Request('http://test.wupo.info/loc/loc-in.php',data)
  try:
    response = urllib.request.urlopen(request)
  except (urllib.error.HTTPError, socket.error,urllib.error.URLError) as e:
    print('Connection error occurred when inserting data.')
    return saveToLocal()
  else:
    r = response.read().decode('utf-8')
    if response.code != 200:
      print("Error code:"+response.code)  
    else:
      print(r)
      return r

def checkAc(c=0):
  global ac
  global ps
  if c==0:
    a= droid.prefGetAll("z").result
    if ('ac' in a):
      ac=a['ac']
      ps=a['ps']
    else:
      line = droid.dialogGetInput('Account Info','Please input your account:')
      ac=str(line.result)
      droid.prefPutValue("ac",ac,"z")
      line1 = droid.dialogGetPassword('Account Info','Please input your password:')
      ps=str(line1.result)
      droid.prefPutValue("ps",ps,"z")
      droid.makeToast('Account '+ac+' saved')
  elif c==1:
      line = droid.dialogGetInput('Account Info','Please input your account:')
      ac=str(line.result)
      droid.prefPutValue("ac",ac,"z")
      line1 = droid.dialogGetPassword('Account Info','Please input your password:')
      ps=str(line1.result)
      droid.prefPutValue("ps",ps,"z")
      droid.makeToast('Account '+ac+' saved')

def doLogin():
  checkAc(0)
  url="http://test.wupo.info/login/login_go.php"
  data=urllib.parse.urlencode({'UserName':ac,'Password':ps})
  data=data.encode('utf-8')
  cj=http.cookiejar.LWPCookieJar()
  cookie_support = urllib.request.HTTPCookieProcessor(cj)
  opener = urllib.request.build_opener(cookie_support, urllib.request.HTTPHandler)
  urllib.request.install_opener(opener)
  req=urllib.request.Request(url = url,data = data)
  rt=''
  try:
    response=urllib.request.urlopen(req)
  except (urllib.error.HTTPError, socket.error, socket.gaierror,urllib.error.URLError) as e:
    print('Connection error, couldn\'t login in.')
    return rt
  else:
    cookies=response.headers["Set-cookie"]
    cookie=cookies[cookies.index("PHPSESSID="):]
    #print(cookies)
    #session = cookie[10:cookie.index(";")+1] 
    rt= response.read().decode('utf-8')
    droid.makeToast(rt)
    return rt

def writeLocation(geoData):
  if (doLogin().startswith('登')):
    return doPost("""$_SESSION["user_id"]""",geoData['latitude'],geoData['longitude'],geoData['accuracy'],geoData['altitude'],'0',geoData['heading'],geoData['speed'],geoData['timestamp'],geoData['readTime'],geoData['text'],geoData['geoCode'])
  else:
    return saveToLocal()

def saveToLocal():
  global geoData
  droid.makeToast("Try to save data to local")
  conn = sqlite3.connect('/storage/sdcard0/home/test.db')
  c = conn.cursor()
  c.execute("""create table if not exists LocalLoc (ID integer primary key not NULL,latitude double,longitude double,accuracy integer,altitude double,heading double,speed double,locTimestamp integer(13),locTime CHAR(20),textMsg TEXT,geoCode TEXT)""")
  s="""insert into LocalLoc (ID,latitude,longitude,accuracy,altitude,heading,speed,locTimestamp,locTime,textMsg,geoCode) VALUES (NULL,"""+geoData['latitude']+','+geoData['longitude']+','+geoData['accuracy']+','+geoData['altitude']+','+geoData['heading']+','+geoData['speed']+',\''+geoData['timestamp']+'\',\''+geoData['readTime']+'\',\''+geoData['text']+'\',\''+geoData['geoCode']+'\')'
  c.execute(s)
  conn.commit()
  c.close
  return "Record at "+geoData['readTime']+" saved to local."

def getgeocode():
  global geoData
  droid.makeToast('Getting geocode...')
  s=''
  try:
    result=droid.geocode( geoData['latitude'], geoData['longitude'], 1).result
  except java.io.IOException:
    print('geo code network failed：java.io.IOException')
  if result==None:
    s=""
  else:
    result=result[0]
    if ('thoroughfare' in result.keys()) and ('feature_name' in result.keys()) and (result['thoroughfare']==result['feature_name']):
      del result['feature_name']
    for k in result:
      s=s+","+str(result[k])
    s=s[1:]
  return s

def getGeoInfo(code):
  while True:
    try:
      """droid.readLocation().result"""
      event=droid.eventWaitFor('location',15000).result
      if ('gps' in event['data']):
        location=event['data']['gps']
        setGeoData(location,code)
        break
      elif ('fused' in event['data']):
        location=event['data']['fused']
        setGeoData(location,code)
        break
      elif ('network' in event['data']):
        location=event['data']['network']
        setGeoData(location,code)
        break
      else:
        print ("No location data type")
    except :
      print ("No location data")
  droid.eventPost('stdout',str(geoData))

def saveGeoInfo(massage):
  global geoData
  geoData['text']=massage
  return writeLocation(geoData)  

droid.webViewShow ('file:///storage/sdcard0/com.hipipal.qpyplus/projects3/loc/index.html')
while True:
  event = droid.eventWait().result
  if event ['name' ] == 'kill' :
    droid.stopLocating()
    droid.makeToast('closing...')
    sys.exit()
  elif event ['name' ] == 'get' :
    droid.startLocating(7000)
    getGeoInfo(event [ 'data' ])
  elif event ['name' ] == 'line' :
    droid.eventPost('insert', saveGeoInfo(event [ 'data' ]))
  elif event ['name' ] == 'upload' :
    lo=doLogin()
    if (lo.startswith('登')):
      upload()
    else:
      droid.eventPost('insert',"Login in failed.")
