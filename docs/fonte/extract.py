import json,os,re
from bs4 import BeautifulSoup
D=os.path.dirname(__file__);idx=json.load(open(D+'/crawl/index.json'))
out=[];md=[]
for u,r in idx.items():
    if r.get('status')!=200 or not os.path.exists(D+'/crawl/'+r['file']):
        out.append({'url':u,'status':r.get('status')});continue
    s=BeautifulSoup(open(D+'/crawl/'+r['file']).read(),'html.parser')
    bc=[x.get_text(strip=True) for x in s.select('#fav-slide .breadcrumb li, #fav-slide ul li')] 
    sb=s.find(id='fav-sidebar1');side=sb.get_text(' | ',strip=True) if sb else ''
    mc=s.find(id='fav-maincontent')
    for t in mc.find_all(['script','style']):t.decompose()
    # to markdown-ish
    lines=[]
    for el in mc.find_all(['h1','h2','h3','h4','p','li','td','th','a']):
        pass
    text=mc.get_text('\n',strip=True)
    links=[(a.get_text(' ',strip=True),a['href']) for a in mc.find_all('a',href=True)]
    h=mc.find(['h1','h2']);title=h.get_text(' ',strip=True) if h else (s.title.text if s.title else '')
    wc=len(text.split())
    out.append({'url':u,'status':200,'title':title,'breadcrumb':' > '.join(bc),'sidebar':side,'words':wc,'files':sorted(set(r['files'])),'n_links':len(links)})
    md.append(f"\n\n################ {u}\nTITOLO: {title}\nBREADCRUMB: {' > '.join(bc)}\nSIDEBAR: {side}\nPAROLE: {wc}\n----\n{text}\n----LINK:\n"+'\n'.join(f'- {t} -> {h}' for t,h in links))
json.dump(out,open(D+'/pages.json','w'),indent=1,ensure_ascii=False)
open(D+'/pages.md','w').write(''.join(md))
print(len(out),sum(1 for o in out if o.get('status')==200))
