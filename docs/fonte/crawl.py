import requests,time,json,os,hashlib,re
from urllib.parse import urljoin,urlparse,urlunparse
from bs4 import BeautifulSoup
OUT=os.path.dirname(__file__)+'/crawl'
H='iea.ing.unipi.it'
def norm(u):
    p=urlparse(u)
    if p.scheme not in('http','https'):return None
    host=p.netloc.lower().replace('www.','')
    if host!=H:return None
    path=p.path.rstrip('/') or '/'
    if not path.startswith('/it'):return None
    if re.search(r'\.(pdf|docx?|xlsx?|pptx?|zip|jpe?g|png|gif)$',path,re.I):return None
    if p.query and not re.match(r'^(start|limitstart)=\d+$',p.query):return None
    return urlunparse(('https',H,path,'',p.query,''))
sess=requests.Session();sess.headers['User-Agent']='Mozilla/5.0 (prototype content audit)'
def get(u):
    for i in range(6):
        try:
            r=sess.get(u,timeout=30)
            if r.status_code in(502,503,504):raise Exception(r.status_code)
            return r
        except Exception as e:
            time.sleep(2**i)
    return None
seen={};queue=['https://'+H+'/it']
def isbach(u):
    rest=u.split('/it/bacheca',1)
    if len(rest)<2:return 0
    if '?' in u:return 2
    return 2 if re.search(r'/\d+-',rest[1]) else 1
cnt={'item':0}
class R: pass
while queue and len(seen)<600:
    queue.sort(key=isbach)
    u=queue.pop(0)
    if u in seen:continue
    if isbach(u)==2:
        cnt['item']+=1
        if cnt['item']>40:seen[u]={'status':'SKIPPED'};continue
    fn0=hashlib.md5(u.encode()).hexdigest()[:12]+'.html'
    if os.path.exists(f'{OUT}/{fn0}'):
        r=R();r.status_code=200;r.url=u;r.text=open(f'{OUT}/{fn0}').read();r.headers={'content-type':'text/html'}
    else:r=get(u)
    if r is None:seen[u]={'status':'ERR'};continue
    fn=hashlib.md5(u.encode()).hexdigest()[:12]+'.html'
    rec={'status':r.status_code,'final':r.url,'file':fn,'links':[],'files':[],'external':[]}
    seen[u]=rec
    if r.status_code!=200 or 'html' not in r.headers.get('content-type',''):continue
    open(f'{OUT}/{fn}','w').write(r.text)
    s=BeautifulSoup(r.text,'html.parser')
    for a in s.find_all('a',href=True):
        full=urljoin(r.url,a['href'].strip())
        n=norm(full)
        if n:
            rec['links'].append(n)
            if n not in seen and n not in queue:queue.append(n)
        elif re.search(r'\.(pdf|docx?|xlsx?|pptx?|zip)(\?|$)',full,re.I):rec['files'].append(full)
        elif full.startswith('http') and H not in full:rec['external'].append(full)
    print(len(seen),len(queue),u,flush=True)
    json.dump(seen,open(OUT+'/index.json','w'),indent=1)
json.dump(seen,open(OUT+'/index.json','w'),indent=1)
