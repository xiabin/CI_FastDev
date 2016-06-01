<?php

/**
 * Response Json
 *
 * Javascript|Ajax|Json 交互响应JSON输出
 *
 * @param bool $status
 * @param string $message
 * @param mixed $data
 * @param string $callback 使用 Callback 则为 JSONP 形式回调
 */
function response($status, $message = '', $data = null, $callback = null)
{
    $output = array('status' => $status);

    if ($message != '') {
        $output['message'] = $message;
    }

    if ($data !== null) {
        $output['data'] = $data;
    }

    $json = json_encode($output, JSON_UNESCAPED_UNICODE);
    echo $callback ? $callback . '(' . $json . ')' : $json;
    exit;
}

/**
 * 返回正确格式的数字
 *
 * @param mixed $var
 * @return string
 */
function numval($var)
{
    if (ctype_digit($var)) {
        return $var;
    }

    if (preg_match('/(\d+)/', $var, $m)) {
        return $m[1];
    }

    return 0;
}

/**
 * @return string
 * 生成guid
 */
function guid()
{
    if (function_exists('com_create_guid')) {
        $uuid = com_create_guid();
    } else {
        mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = substr($charid, 0, 8) . $hyphen
            . substr($charid, 8, 4) . $hyphen
            . substr($charid, 12, 4) . $hyphen
            . substr($charid, 16, 4) . $hyphen
            . substr($charid, 20, 12)
            . chr(125);// "}"

    }
    return trim($uuid, '{}');
}


/**
 * 获取客户端 IP 地址
 * @return string
 */
function ip_address()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) $ip = $_SERVER['HTTP_CLIENT_IP']; elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    elseif (!empty($_SERVER['REMOTE_ADDR'])) $ip = $_SERVER['REMOTE_ADDR'];
    else $ip = '';
    preg_match('/[\d\.]{7,15}/', $ip, $ips);
    return !empty($ips[0]) ? $ips[0] : '';
}


/***
 * @param $url
 * @return bool获取域名
 */
function find_tld($url)
{

    $purl = parse_url($url);
    $host = strtolower($purl['host']);

    $valid_tlds = ".ab.ca .bc.ca .mb.ca .nb.ca .nf.ca .nl.ca .ns.ca .nt.ca .nu.ca .on.ca .pe.ca .qc.ca .sk.ca .yk.ca .com.cd .net.cd .org.cd .com.ch .net.ch .org.ch .gov.ch .co.ck .ac.cn .com.cn .edu.cn .gov.cn .net.cn .org.cn .ah.cn .bj.cn .cq.cn .fj.cn .gd.cn .gs.cn .gz.cn .gx.cn .ha.cn .hb.cn .he.cn .hi.cn .hl.cn .hn.cn .jl.cn .js.cn .jx.cn .ln.cn .nm.cn .nx.cn .qh.cn .sc.cn .sd.cn .sh.cn .sn.cn .sx.cn .tj.cn .xj.cn .xz.cn .yn.cn .zj.cn .com.co .edu.co .org.co .gov.co .mil.co .net.co .nom.co .com.cu .edu.cu .org.cu .net.cu .gov.cu .inf.cu .gov.cx .edu.do .gov.do .gob.do .com.do .org.do .sld.do .web.do .net.do .mil.do .art.do .com.dz .org.dz .net.dz .gov.dz .edu.dz .asso.dz .pol.dz .art.dz .com.ec .info.ec .net.ec .fin.ec .med.ec .pro.ec .org.ec .edu.ec .gov.ec .mil.ec .com.ee .org.ee .fie.ee .pri.ee .eun.eg .edu.eg .sci.eg .gov.eg .com.eg .org.eg .net.eg .mil.eg .com.es .nom.es .org.es .gob.es .edu.es .com.et .gov.et .org.et .edu.et .net.et .biz.et .name.et .info.et .co.fk .org.fk .gov.fk .ac.fk .nom.fk .net.fk .tm.fr .asso.fr .nom.fr .prd.fr .presse.fr .com.fr .gouv.fr .com.ge .edu.ge .gov.ge .org.ge .mil.ge .net.ge .pvt.ge .co.gg .net.gg .org.gg .com.gi .ltd.gi .gov.gi .mod.gi .edu.gi .org.gi .com.gn .ac.gn .gov.gn .org.gn .net.gn .com.gr .edu.gr .net.gr .org.gr .gov.gr .com.hk .edu.hk .gov.hk .idv.hk .net.hk .org.hk .com.hn .edu.hn .org.hn .net.hn .mil.hn .gob.hn .iz.hr .from.hr .name.hr .com.hr .com.ht .net.ht .firm.ht .shop.ht .info.ht .pro.ht .adult.ht .org.ht .art.ht .pol.ht .rel.ht .asso.ht .perso.ht .coop.ht .med.ht .edu.ht .gouv.ht .gov.ie .co.in .firm.in .net.in .org.in .gen.in .ind.in .nic.in .ac.in .edu.in .res.in .gov.in .mil.in .ac.ir .co.ir .gov.ir .net.ir .org.ir .sch.ir .gov.it .co.je .net.je .org.je .edu.jm .gov.jm .com.jm .net.jm .com.jo .org.jo .net.jo .edu.jo .gov.jo .mil.jo .co.kr .or.kr .com.kw .edu.kw .gov.kw .net.kw .org.kw .mil.kw .edu.ky .gov.ky .com.ky .org.ky .net.ky .org.kz .edu.kz .net.kz .gov.kz .mil.kz .com.kz .com.li .net.li .org.li .gov.li .gov.lk .sch.lk .net.lk .int.lk .com.lk .org.lk .edu.lk .ngo.lk .soc.lk .web.lk .ltd.lk .assn.lk .grp.lk .hotel.lk .com.lr .edu.lr .gov.lr .org.lr .net.lr .org.ls .co.ls .gov.lt .mil.lt .gov.lu .mil.lu .org.lu .net.lu .com.lv .edu.lv .gov.lv .org.lv .mil.lv .id.lv .net.lv .asn.lv .conf.lv .com.ly .net.ly .gov.ly .plc.ly .edu.ly .sch.ly .med.ly .org.ly .id.ly .co.ma .net.ma .gov.ma .org.ma .tm.mc .asso.mc .org.mg .nom.mg .gov.mg .prd.mg .tm.mg .com.mg .edu.mg .mil.mg .com.mk .org.mk .com.mo .net.mo .org.mo .edu.mo .gov.mo .org.mt .com.mt .gov.mt .edu.mt .net.mt .com.mu .co.mu .aero.mv .biz.mv .com.mv .coop.mv .edu.mv .gov.mv .info.mv .int.mv .mil.mv .museum.mv .name.mv .net.mv .org.mv .pro.mv .com.mx .net.mx .org.mx .edu.mx .gob.mx .com.my .net.my .org.my .gov.my .edu.my .mil.my .name.my .edu.ng .com.ng .gov.ng .org.ng .net.ng .gob.ni .com.ni .edu.ni .org.ni .nom.ni .net.ni .gov.nr .edu.nr .biz.nr .info.nr .com.nr .net.nr .ac.nz .co.nz .cri.nz .gen.nz .geek.nz .govt.nz .iwi.nz .maori.nz .mil.nz .net.nz .org.nz .school.nz .com.pf .org.pf .edu.pf .com.pg .net.pg .com.ph .gov.ph .com.pk .net.pk .edu.pk .org.pk .fam.pk .biz.pk .web.pk .gov.pk .gob.pk .gok.pk .gon.pk .gop.pk .gos.pk .com.pl .biz.pl .net.pl .art.pl .edu.pl .org.pl .ngo.pl .gov.pl .info.pl .mil.pl .waw.pl .warszawa.pl .wroc.pl .wroclaw.pl .krakow.pl .poznan.pl .lodz.pl .gda.pl .gdansk.pl .slupsk.pl .szczecin.pl .lublin.pl .bialystok.pl .olsztyn.pl .torun.pl .biz.pr .com.pr .edu.pr .gov.pr .info.pr .isla.pr .name.pr .net.pr .org.pr .pro.pr .edu.ps .gov.ps .sec.ps .plo.ps .com.ps .org.ps .net.ps .com.pt .edu.pt .gov.pt .int.pt .net.pt .nome.pt .org.pt .publ.pt .net.py .org.py .gov.py .edu.py .com.py .com.ru .net.ru .org.ru .pp.ru .msk.ru .int.ru .ac.ru .gov.rw .net.rw .edu.rw .ac.rw .com.rw .co.rw .int.rw .mil.rw .gouv.rw .com.sa .edu.sa .sch.sa .med.sa .gov.sa .net.sa .org.sa .pub.sa .com.sb .gov.sb .net.sb .edu.sb .com.sc .gov.sc .net.sc .org.sc .edu.sc .com.sd .net.sd .org.sd .edu.sd .med.sd .tv.sd .gov.sd .info.sd .org.se .pp.se .tm.se .parti.se .press.se .ab.se .c.se .d.se .e.se .f.se .g.se .h.se .i.se .k.se .m.se .n.se .o.se .s.se .t.se .u.se .w.se .x.se .y.se .z.se .ac.se .bd.se .com.sg .net.sg .org.sg .gov.sg .edu.sg .per.sg .idn.sg .edu.sv .com.sv .gob.sv .org.sv .red.sv .gov.sy .com.sy .net.sy .ac.th .co.th .in.th .go.th .mi.th .or.th .net.th .ac.tj .biz.tj .com.tj .co.tj .edu.tj .int.tj .name.tj .net.tj .org.tj .web.tj .gov.tj .go.tj .mil.tj .com.tn .intl.tn .gov.tn .org.tn .ind.tn .nat.tn .tourism.tn .info.tn .ens.tn .fin.tn .net.tn .gov.to .gov.tp .com.tr .info.tr .biz.tr .net.tr .org.tr .web.tr .gen.tr .av.tr .dr.tr .bbs.tr .name.tr .tel.tr .gov.tr .bel.tr .pol.tr .mil.tr .k12.tr .edu.tr .co.tt .com.tt .org.tt .net.tt .biz.tt .info.tt .pro.tt .name.tt .edu.tt .gov.tt .gov.tv .edu.tw .gov.tw .mil.tw .com.tw .net.tw .org.tw .idv.tw .game.tw .ebiz.tw .club.tw .co.tz .ac.tz .go.tz .or.tz .ne.tz .com.ua .gov.ua .net.ua .edu.ua .org.ua .cherkassy.ua .ck.ua .chernigov.ua .cn.ua .chernovtsy.ua .cv.ua .crimea.ua .dnepropetrovsk.ua .dp.ua .donetsk.ua .dn.ua .if.ua .kharkov.ua .kh.ua .kherson.ua .ks.ua .khmelnitskiy.ua .km.ua .kiev.ua .kv.ua .kirovograd.ua .kr.ua .lugansk.ua .lg.ua .lutsk.ua .lviv.ua .nikolaev.ua .mk.ua .odessa.ua .od.ua .poltava.ua .pl.ua .rovno.ua .rv.ua .sebastopol.ua .sumy.ua .ternopil.ua .te.ua .uzhgorod.ua .vinnica.ua .vn.ua .zaporizhzhe.ua .zp.ua .zhitomir.ua .zt.ua .co.ug .ac.ug .sc.ug .go.ug .ne.ug .or.ug .ac.uk .co.uk .gov.uk .ltd.uk .me.uk .mil.uk .mod.uk .net.uk .nic.uk .nhs.uk .org.uk .plc.uk .police.uk .bl.uk .icnet.uk .jet.uk .nel.uk .nls.uk .parliament.uk .sch.uk .ak.us .al.us .ar.us .az.us .ca.us .co.us .ct.us .dc.us .de.us .dni.us .fed.us .fl.us .ga.us .hi.us .ia.us .id.us .il.us .in.us .isa.us .kids.us .ks.us .ky.us .la.us .ma.us .md.us .me.us .mi.us .mn.us .mo.us .ms.us .mt.us .nc.us .nd.us .ne.us .nh.us .nj.us .nm.us .nsn.us .nv.us .ny.us .oh.us .ok.us .or.us .pa.us .ri.us .sc.us .sd.us .tn.us .tx.us .ut.us .vt.us .va.us .wa.us .wi.us .wv.us .wy.us .edu.uy .gub.uy .org.uy .com.uy .net.uy .mil.uy .com.ve .net.ve .org.ve .info.ve .co.ve .web.ve .com.vi .org.vi .edu.vi .gov.vi .com.vn .net.vn .org.vn .edu.vn .gov.vn .int.vn .ac.vn .biz.vn .info.vn .name.vn .pro.vn .health.vn .com.ye .net.ye .ac.yu .co.yu .org.yu .edu.yu .ac.za .city.za .co.za .edu.za .gov.za .law.za .mil.za .nom.za .org.za .school.za .alt.za .net.za .ngo.za .tm.za .web.za .co.zm .org.zm .gov.zm .sch.zm .ac.zm .co.zw .org.zw .gov.zw .ac.zw .com.ac .edu.ac .gov.ac .net.ac .mil.ac .org.ac .nom.ad .net.ae .co.ae .gov.ae .ac.ae .sch.ae .org.ae .mil.ae .pro.ae .name.ae .com.ag .org.ag .net.ag .co.ag .nom.ag .off.ai .com.ai .net.ai .org.ai .gov.al .edu.al .org.al .com.al .net.al .com.am .net.am .org.am .com.ar .net.ar .org.ar .e164.arpa .ip6.arpa .uri.arpa .urn.arpa .gv.at .ac.at .co.at .or.at .com.au .net.au .asn.au .org.au .id.au .csiro.au .gov.au .edu.au .com.aw .com.az .net.az .org.az .com.bb .edu.bb .gov.bb .net.bb .org.bb .com.bd .edu.bd .net.bd .gov.bd .org.bd .mil.be .ac.be .gov.bf .com.bm .edu.bm .org.bm .gov.bm .net.bm .com.bn .edu.bn .org.bn .net.bn .com.bo .org.bo .net.bo .gov.bo .gob.bo .edu.bo .tv.bo .mil.bo .int.bo .agr.br .am.br .art.br .edu.br .com.br .coop.br .esp.br .far.br .fm.br .g12.br .gov.br .imb.br .ind.br .inf.br .mil.br .net.br .org.br .psi.br .rec.br .srv.br .tmp.br .tur.br .tv.br .etc.br .adm.br .adv.br .arq.br .ato.br .bio.br .bmd.br .cim.br .cng.br .cnt.br .ecn.br .eng.br .eti.br .fnd.br .fot.br .fst.br .ggf.br .jor.br .lel.br .mat.br .med.br .mus.br .not.br .ntr.br .odo.br .ppg.br .pro.br .psc.br .qsl.br .slg.br .trd.br .vet.br .zlg.br .dpn.br .nom.br .com.bs .net.bs .org.bs .com.bt .edu.bt .gov.bt .net.bt .org.bt .co.bw .org.bw .gov.by .mil.by .ac.cr .co.cr .ed.cr .fi.cr .go.cr .or.cr .sa.cr .com.cy .biz.cy .info.cy .ltd.cy .pro.cy .net.cy .org.cy .name.cy .tm.cy .ac.cy .ekloges.cy .press.cy .parliament.cy .com.dm .net.dm .org.dm .edu.dm .gov.dm .biz.fj .com.fj .info.fj .name.fj .net.fj .org.fj .pro.fj .ac.fj .gov.fj .mil.fj .school.fj .com.gh .edu.gh .gov.gh .org.gh .mil.gh .co.hu .info.hu .org.hu .priv.hu .sport.hu .tm.hu .2000.hu .agrar.hu .bolt.hu .casino.hu .city.hu .erotica.hu .erotika.hu .film.hu .forum.hu .games.hu .hotel.hu .ingatlan.hu .jogasz.hu .konyvelo.hu .lakas.hu .media.hu .news.hu .reklam.hu .sex.hu .shop.hu .suli.hu .szex.hu .tozsde.hu .utazas.hu .video.hu .ac.id .co.id .or.id .go.id .ac.il .co.il .org.il .net.il .k12.il .gov.il .muni.il .idf.il .co.im .net.im .gov.im .org.im .nic.im .ac.im .org.jm .ac.jp .ad.jp .co.jp .ed.jp .go.jp .gr.jp .lg.jp .ne.jp .or.jp .hokkaido.jp .aomori.jp .iwate.jp .miyagi.jp .akita.jp .yamagata.jp .fukushima.jp .ibaraki.jp .tochigi.jp .gunma.jp .saitama.jp .chiba.jp .tokyo.jp .kanagawa.jp .niigata.jp .toyama.jp .ishikawa.jp .fukui.jp .yamanashi.jp .nagano.jp .gifu.jp .shizuoka.jp .aichi.jp .mie.jp .shiga.jp .kyoto.jp .osaka.jp .hyogo.jp .nara.jp .wakayama.jp .tottori.jp .shimane.jp .okayama.jp .hiroshima.jp .yamaguchi.jp .tokushima.jp .kagawa.jp .ehime.jp .kochi.jp .fukuoka.jp .saga.jp .nagasaki.jp .kumamoto.jp .oita.jp .miyazaki.jp .kagoshima.jp .okinawa.jp .sapporo.jp .sendai.jp .yokohama.jp .kawasaki.jp .nagoya.jp .kobe.jp .kitakyushu.jp .per.kh .com.kh .edu.kh .gov.kh .mil.kh .net.kh .org.kh .net.lb .org.lb .gov.lb .edu.lb .com.lb .com.lc .org.lc .edu.lc .gov.lc .army.mil .navy.mil .weather.mobi .music.mobi .ac.mw .co.mw .com.mw .coop.mw .edu.mw .gov.mw .int.mw .museum.mw .net.mw .org.mw .mil.no .stat.no .kommune.no .herad.no .priv.no .vgs.no .fhs.no .museum.no .fylkesbibl.no .folkebibl.no .idrett.no .com.np .org.np .edu.np .net.np .gov.np .mil.np .org.nr .com.om .co.om .edu.om .ac.com .sch.om .gov.om .net.om .org.om .mil.om .museum.om .biz.om .pro.om .med.om .com.pa .ac.pa .sld.pa .gob.pa .edu.pa .org.pa .net.pa .abo.pa .ing.pa .med.pa .nom.pa .com.pe .org.pe .net.pe .edu.pe .mil.pe .gob.pe .nom.pe .law.pro .med.pro .cpa.pro .vatican.va .ac .ad .ae .aero .af .ag .ai .al .am .an .ao .aq .ar .arpa .as .at .au .aw .az .ba .bb .bd .be .bf .bg .bh .bi .biz .bj .bm .bn .bo .br .bs .bt .bv .bw .by .bz .ca .cat .cc .cd .cf .cg .ch .ci .ck .cl .cm .cn .co .com .coop .cr .cu .cv .cx .cy .cz .de .dj .dk .dm .do .dz .ec .edu .ee .eg .er .es .et .eu .fi .fj .fk .fm .fo .fr .ga .gb .gd .ge .gf .gg .gh .gi .gl .gm .gov .gp .gq .gr .gs .gt .gu .gw .gy .hk .hm .hn .hr .ht .hu .id .ie .il .im .in .info .int .io .iq .ir .is .it .je .jm .jo .jobs .jp .ke .kg .kh .ki .km .kn .kr .kw .ky .kz .la .lb .lc .li .lk .lr .ls .lt .lu .lv .ly .ma .mc .md .mg .mh .mil .mk .ml .mm .mn .mo .mobi .mp .mq .mr .ms .mt .mu .museum .mv .mw .na .name .nc .ne .net .nf .ng .ni .nl .no .np .nr .nu .nz .om .org .pa .pe .pf .pg .ph .pk .pl .pm .pn .post .pr .pro .ps .pt .pw .py .qa .re .ro .ru .rw .sa .sb .sc .sd .se .sg .sh .si .sj .sk .sl .sm .sn .so .sr .st .su .sv .sy .sz .tc .td .tf .tg .th .tj .tk .tl .tm .tn .to .tp .tr .travel .tt .tv .tw .tz .ua .ug .uk .um .us .uy .uz .va .vc .ve .vg .vi .vn .vuwf .ye .yt .yu .za .zm .zw .ca .cd .ch .cn .cu .cx .dm .dz .ec .ee .es .fr .ge .gg .gi .gr .hk .hn .hr .ht .hu .ie .in .ir .it .je .jo .jp .kr .ky .li .lk .lt .lu .lv .ly .ma .mc .mg .mk .mo .mt .mu .nl .no .nr .nr .pf .ph .pk .pl .pr .ps .pt .ro .ru .rw .sc .sd .se .sg .tj .to .to .tt .tv .tw .tw .tw .tw .ua .ug .us .vi .vn";


    $tld_regex = '#(.*?)([^.]+)(' . str_replace(array('.', ' '), array('\\.', '|'), $valid_tlds) . ')$#';

    //remove the extension
    preg_match($tld_regex, $host, $matches);

    if (!empty($matches) && sizeof($matches) > 2) {
        $extension = array_pop($matches);
        $tld = array_pop($matches);
        return $tld . $extension;

    } else { //change to "false" if you prefer
        return $host;
    }
}
