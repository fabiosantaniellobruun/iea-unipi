import requests,time,json,os,sys
from concurrent.futures import ThreadPoolExecutor
D=os.path.dirname(os.path.abspath(__file__))+'/art'
kind=sys.argv[1]; lo,hi=int(sys.argv[2]),int(sys.argv[3])
URL={'art':'https://iea.ing.unipi.it/index.php?option=com_content&view=article&id=%d',
     'ev':'https://iea.ing.unipi.it/index.php?option=com_icagenda&view=event&id=%d',
     'cat':'https://iea.ing.unipi.it/index.php?option=com_content&view=category&id=%d&limit=0'}[kind]
def one(i):
    fn=f'{D}/{kind}{i}.html'
    if os.path.exists(fn) or os.path.exists(fn+'.404'):return
    for k in range(6):
        try:
            r=requests.get(URL%i,timeout=90,headers={'User-Agent':'Mozilla/5.0 (content audit)'})
            if r.status_code==404:open(fn+'.404','w').write(r.url);print(i,404,flush=True);return
            if r.status_code==200:open(fn,'w').write(r.url+'\n'+r.text);print(i,200,flush=True);return
            raise Exception(r.status_code)
        except Exception as e:time.sleep(2**k)
    print(i,'ERR',flush=True)
with ThreadPoolExecutor(6) as ex:list(ex.map(one,range(lo,hi+1)))
print('DONE',flush=True)
