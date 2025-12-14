<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Perl Fixture Gen.</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

</head>
<body>

<script>

const fixtureConfig = {
    Mandatory: {
        Title: "Victorian Pennant Snooker",
        Season: "Pennant Billiards Fixtures 2003",
        NameFile: "Desktop/2003Billiards/clubs.ini",
        KeyFile: "Desktop/2003Billiards/vbsakeys.ini",
        DateFile: "Desktop/2003Billiards/dates.ini",
        Fixture: "Desktop/2003Billiards/billfix.html"
    },
    Optional: {
        Owner: "Victorian Billiards & Snooker Association",
        TeamWidth: 22,
        HomeWidth: 32,
        GradeWidth: 28,
        PageDepth: 54,
        HomePage: "https://vbsa.org.au",
        Contact: "Contact",
        Grounds: "Grounds",
        Notes: "Notes",
        Teams: "Teams"
    },
    GRADES: {
        BAGrade: {},
        BBGrade: {},
        SAGrade: {},
        SB1Grade: {},
        SB2Grade: {},
        SC1Grade: {},
        SC2Grade: {},
        SC3Grade: {}
    },
    BAGrade: {
        Name: "A Grade Pennant Billiards",
        Dates: "wednesday",
        Day: "Wednesday",
        Key: "vbsa.6"
    },
    BBGrade: {
        Name: "B Grade Pennant Billiards",
        Dates: "monday",
        Day: "Monday",
        Key: "vbsa.6"
    },
    SAGrade: {
        Name: "A Grade Snooker",
        Dates: "monday",
        Day: "Monday",
        Key: "vbsa.6"
    },
    SB1Grade: {
        Name: "B Grade Snooker Section 1",
        Dates: "wednesday",
        Day: "Wednesday",
        Key: "vbsa.8"
    },
    SB2Grade: {
        Name: "B Grade Snooker Section 2",
        Dates: "wednesday",
        Day: "Wednesday",
        Key: "vbsa.6"
    },
    SC1Grade: {
        Name: "C Grade Snooker Section 1",
        Dates: "monday",
        Day: "Monday",
        Key: "vbsa.6was8"
    },
    SC2Grade: {
        Name: "C Grade Snooker Section 2",
        Dates: "monday",
        Day: "Monday",
        Key: "vbsa.8"
    },
    SC3Grade: {
        Name: "C Grade Snooker Section 3",
        Dates: "wednesday",
        Day: "Wednesday",
        Key: "vbsa.6"
    },
    ALLOCATION: {
        wednesday: {
            BAGrade: ["Yarraville", "Cheltenham", "Kooyong", "Brunswick", "RACV", "Bye"],
            SB1Grade: ["Cheltenham", "Yarraville", "Princes", "Princes", "Brunswick", "Bentleigh", "CueClub", "RedTriang"],
            SB2Grade: ["DandClub", "Clayton", "Princes", "Princes", "Brunswick", "Maccabi"],
            SC3Grade: ["RACV", "Yarraville", "Princes", "Princes", "Brunswick", "Princes", "Maccabi", "FastEddies"]
        },
        monday: {
            BBGrade: ["RACV", "Cheltenham", "StMarys", "Princes", "Brunswick", "Bye"],
            SAGrade: ["Cheltenham", "RACV", "Yarraville", "Princes", "Brunswick", "Brunswick"],
            SC1Grade: ["DandWSC", "DandClub", "Princes", "Yarraville", "Maccabi", "Bye", "Bye", "Bentleigh"],
            SC2Grade: ["DandClub", "DandWSC", "RACV", "Princes", "Brunswick", "Bye", "Bentleigh", "NBrighton"]
        }
    }
};

const vbsa = {
    "vbsa.4": {
        1: "1 v 2   3 v 4",
        2: "2 v 3   4 v 1",
        3: "1 v 3   4 v 2",
        4: "2 v 1   4 v 3",
        5: "3 v 2   1 v 4",
        6: "3 v 1   2 v 4",
        7: "1 v 2   3 v 4",
        8: "2 v 3   4 v 1",
        9: "1 v 3   4 v 2",
        10: "2 v 1   4 v 3",
        11: "3 v 2   1 v 4",
        12: "3 v 1   2 v 4"
    },
    "vbsa.6": {
        1: "1 v 6   3 v 4   5 v 2",
        2: "2 v 3   4 v 1   6 v 5",
        3: "1 v 2   3 v 5   6 v 4",
        4: "2 v 6   3 v 1   5 v 4",
        5: "1 v 5   4 v 2   6 v 3",
        6: "6 v 1   4 v 3   2 v 5",
        7: "3 v 2   1 v 4   5 v 6",
        8: "2 v 1   5 v 3   4 v 6",
        9: "6 v 2   1 v 3   4 v 5",
        10: "5 v 1   2 v 4   3 v 6",
        11: "1 v 6   3 v 4   5 v 2",
        12: "2 v 3   4 v 1   6 v 5",
        13: "1 v 2   3 v 5   6 v 4",
        14: "2 v 6   3 v 1   5 v 4",
        15: "1 v 5   4 v 2   6 v 3"
    },
    "vbsa.8": {
        1: "1 v 3   4 v 2   6 v 7   8 v 5",
        2: "2 v 6   3 v 4   5 v 1   7 v 8",
        3: "1 v 7   3 v 5   6 v 4   8 v 2",
        4: "2 v 1   4 v 5   6 v 8   7 v 3",
        5: "1 v 6   3 v 2   5 v 7   8 v 4",
        6: "2 v 5   4 v 7   6 v 3   8 v 1",
        7: "1 v 4   3 v 8   5 v 6   7 v 2",
        8: "3 v 1   2 v 4   7 v 6   5 v 8",
        9: "6 v 2   4 v 3   1 v 5   8 v 7",
        10: "7 v 1   5 v 3   4 v 6   2 v 8",
        11: "1 v 2   5 v 4   8 v 6   3 v 7",
        12: "6 v 1   2 v 3   7 v 5   4 v 8",
        13: "5 v 2   7 v 4   3 v 6   1 v 8",
        14: "4 v 1   8 v 3   6 v 5   2 v 7"
    },
    "vbsa.6was8": {
        1: "1 v 3   4 v 2   8 v 5",
        2: "2 v 8   3 v 4   5 v 1",
        3: "1 v 4   3 v 5   8 v 2",
        4: "2 v 1   4 v 5   3 v 8",
        5: "1 v 5   3 v 2   8 v 4",
        6: "2 v 5   4 v 3   8 v 1",
        7: "1 v 4   3 v 8   5 v 2",
        8: "3 v 1   2 v 4   5 v 8",
        9: "8 v 2   4 v 3   1 v 5",
        10: "4 v 1   5 v 3   2 v 8",
        11: "1 v 2   5 v 4   8 v 3",
        12: "5 v 1   2 v 3   4 v 8",
        13: "5 v 2   3 v 4   1 v 8",
        14: "4 v 1   8 v 3   2 v 5"
    },
    "vbsa.10": {
        1: "4 v 3   1 v 7   6 v 10  8 v 2   9 v 5",
        2: "2 v 6   3 v 9  10 v 4   5 v 1   7 v 8",
        3: "1 v 4   3 v 6   5 v 10  7 v 2   9 v 8",
        4: "4 v 7   2 v 5  10 v 3   6 v 9   8 v 1",
        5: "1 v 6   3 v 2   5 v 7   8 v 4   9 v 10",
        6: "4 v 5   2 v 9  10 v 1   6 v 8   7 v 3",
        7: "1 v 2   3 v 5   6 v 4   8 v 10  9 v 7",
        8: "2 v 10  3 v 1   5 v 8   7 v 6   9 v 4",
        9: "4 v 2   1 v 9  10 v 7   6 v 5   8 v 3",
        10: "3 v 4   7 v 1  10 v 6   2 v 8   5 v 9",
        11: "6 v 2   9 v 3   4 v 10  1 v 5   8 v 7",
        12: "4 v 1   6 v 3  10 v 5   2 v 7   8 v 9",
        13: "7 v 4   5 v 2   3 v 10  9 v 6   1 v 8",
        14: "6 v 1   2 v 3   7 v 5   4 v 8  10 v 9"
    }
};

const dates = {
    monday: {
        1: "Monday 4 August",
        2: "Monday 11 August",
        3: "Monday 18 August",
        4: "Monday 25 August",
        5: "Monday 1 September",
        6: "Monday 8 September",
        7: "Monday 15 September",
        8: "Monday 22 September",
        9: "Monday 29 September",
        10: "Monday 6 October",
        11: "Monday 13 October",
        12: "Monday 20 October",
        13: "Monday 27 October",
        14: "Monday 3 November",
        15: "Monday 10 November",
        16: "Monday 17 November",
        17: "Monday 24 November",
        18: "Monday 1 December"
    },
    wednesday: {
        1: "Wednesday 6 August",
        2: "Wednesday 13 August",
        3: "Wednesday 20 August",
        4: "Wednesday 27 August",
        5: "Wednesday 3 September",
        6: "Wednesday 10 September",
        7: "Wednesday 17 September",
        8: "Wednesday 24 September",
        9: "Wednesday 1 October",
        10: "Wednesday 8 October",
        11: "Wednesday 15 October",
        12: "Wednesday 22 October",
        13: "Wednesday 29 October",
        14: "Wednesday 5 November",
        15: "Wednesday 12 November",
        16: "Wednesday 19 November",
        17: "Wednesday 26 November",
        18: "Wednesday 3 December"
    }
};

const clubs = {
    Bentleigh: {
        Name: "Bentleigh",
        FullName: "The Bentleigh Club",
        Address: "Yawla Street Bentleigh",
        Telephone: "9557 7938",
        Contact: "",
        Teams: [",Demons", "", "", "", "", "", "", "", ""],
        Grounds: ["BentleighA", "BentleighA", "BentleighA"]
    },
    Brunswick: {
        Name: "Brunswick",
        FullName: "Brunswick Club",
        Address: "203 Sydney Road Brunswick",
        Telephone: "9380 5144",
        Contact: "",
        Teams: ["", "", "Black", "White", "", "", "", "", "", ""],
        Grounds: ["BrunswickA", "BrunswickC", "BrunswickA", "BrunswickA", "BrunswickB", "BrunswickA", "BrunswickB", "BrunswickC"]
    },
    Cheltenham: {
        Name: "Cheltenham",
        FullName: "Cheltenham Billiards & Snooker Club",
        Address: "143 Weatherall Road Cheltenham",
        Telephone: "9584 3595",
        Contact: "",
        Teams: ["", "", "", "", "", "", ""],
        Grounds: ["CheltenhamA", "CheltenhamA", "CheltenhamA", "CheltenhamA"]
    },
    Clayton: {
        Name: "Clayton RSL",
        FullName: "Clayton RSL",
        Address: "163 Carinish Road Clayton",
        Telephone: "9554 1035",
        Contact: "",
        Teams: ["", "", "", "", "", "", ""],
        Grounds: ["ClaytonA", "ClaytonA"]
    },
    CueClub: {
        Name: "Cue Club",
        FullName: "Cue Club",
        Address: "277 Brunswick Street Fitzroy",
        Telephone: "9419 7284",
        Internet: "",
        Contact: "",
        Teams: ["", "", "", "", "", "", ""],
        Grounds: ["CueClubA", "CueClubA"]
    },
    DandClub: {
        Name: "Dandenong Club",
        FullName: "Dandenong Club",
        Address: "1579 Heatherton Road Dandenong",
        Telephone: "9792 1963",
        Internet: "",
        Contact: "",
        Teams: ["", "", "", "", "", "", ""],
        Grounds: ["DandClubA", "DandClubA", "DandClubA"]
    },
    DandWSC: {
        Name: "Dandenong WSC",
        FullName: "Dandenong Workers Social Club",
        Address: "48 Wedge St Dandenong",
        Telephone: "9792 5064",
        Internet: "",
        Contact: "",
        Teams: ["", "", "", "", "", "", ""],
        Grounds: ["DandWSCA", "DandWSCA", "DandWSCA", "DandClubWSCA"]
    },
    FastEddies: {
        Name: "Fast Eddie's",
        FullName: "Fast Eddie's Snooker Room",
        Address: "168 Chesterville Road Moorabbin",
        Telephone: "9532 2775",
        Contact: "",
        Teams: ["", "", "", "", "", "", ""],
        Grounds: ["FastEddiesA", "FastEddiesA"]
    },
    Kooyong: {
        Name:      "Kooyong",
        FullName:  "Kooyong Lawn Tennis Club",
        Address:   "489 Glenferrie Road Kooyong",
        Telephone: "9822 3333",
        Contact: "",
        Teams: ["", "", "", "", "", "", ""],
        Grounds: ["KooyongA"]
    },
/*
    Maccabi: {
Name      = Maccabi
FullName  = Maccabi Ajax Snooker Club
Address   = 1068 Dandenong Road Carnegie
Telephone = 9571 9473
Contact   = 
Teams     = ,,,,,,
Grounds   = PrincesM PrincesM PrincesN

    NBrighton: {
Name      = North Brighton
FullName  = North Brighton Club
Address   = 14 Warleigh Grove Brighton
Telephone = 9596 6203
Contact   = 
Teams     = ,,,,,,
Grounds   = NBrightonA

    Prahran: {
Name      = Prahran
FullName  = Prahran Club
Address   = 258 High St Prahran
Telephone = 9596 6203
Contact   = 
Teams     = ,,,,,,
Grounds   = PrahranA

[Princes]
Name      = Princes
FullName  = Prince's Snooker Centre
Address   = 1068 Dandenong Road Carnegie
Telephone = 9571 9473
Contact   = 
Teams     = ,,Blue,Red,Blue,Red,,,Red,Blue,Lions,,
Grounds   = PrincesA PrincesB PrincesA PrincesA PrincesB PrincesB PrincesC PrincesC PrincesC PrincesC PrincesD

[RackEmUp]
Name      = Rack-Em-Up
FullName  = Rack-Em-Up Snooker Room
Address   = 28 John St Lilydale
Telephone = 9739 7888
Internet  = 
Contact   = 
Teams     = ,,,,,,
Grounds   = RackEmUpA

[RACV]
Name      = RACV
FullName  = RACV Club
Address   = 123 Queen Street Melbourne
Telephone = 9607 2222
Internet  = www.racv.com.au
Contact   = 
Teams     = ,,,,,,
Grounds   = RACVA RACVA RACVA RACVB RACVB

[RedTriang]
Name      = Red Triangle
FullName  = Red Triangle Snooker Room
Address   = 110a Argyle Street Fitzroy
Telephone = 9419 7330
Contact   = 
Teams     = ,,,,,,
Grounds   = RedTriangA RedTriangA

[StMarys]
Name      = St. Mary's
FullName  = St. Mary's Club
Address   = Bowen Street Camberwell
Telephone = 
Internet  = 
Contact   =  
Teams     = ,,,,,,
Grounds   = StMarysA

[SandyClub]
Name      = Sandringham
FullName  = Sandringham Club
Address   = 92 Beach Road Sandringham
Telephone = 9598 1322
Internet  = 
Contact   = 
Teams     = ,,,,,,
Grounds   = SandyClubA

[Yarraville]
Name      = Yarraville
FullName  = Yarraville Club
Address   = 135 Stephen Street Yarraville
Telephone = 9689 6033
Contact   = 
Teams     = ,,,,,,
Grounds   = YarravilleA YarravilleA YarravilleA  YarravilleB YarravilleB
*/
    GROUNDS: [
        "BentleighA", 
        "BrunswickA", 
        "BrunswickB", 
        "BrunswickC", 
        "CheltenhamA", 
        "ClaytonA", 
        "CueClubA", 
        "DandClubA", 
        "DandWSCA", 
        "FastEddiesA", 
        "KooyongA", 
        "NBrightonA", 
        "PrahranA", 
        "PrincesA", 
        "PrincesB ", 
        "RackEmUpA", 
        "RACVA", 
        "RedTriangA", 
        "StMarysA", 
        "SandyClubA", 
        "YarravilleA", 
        "YarravilleB"
    ],

    BentleighA: {
        Name:      "Bentleigh Club",
        Address:   "Yawla St Bentleigh",
        Location:  "68 B11"
    }

/*
[BentleighA]
Name      = Bentleigh Club
Address   = Yawla St Bentleigh
Location  = 68 B11

[BrunswickA]
Name      = Brunswick Club
Address   = 203 Sydney Road Brunswick
Location  = 29 G9

[BrunswickB]
Name      = Brunswick Club
Address   = 203 Sydney Road Brunswick
Location  = 29 G9

[BrunswickC]
Name      = Brunswick Club
Address   = 203 Sydney Road Brunswick
Location  = 29 G9

[CheltenhamA]
Name      = Cheltenham Club
Address   = 143 Weatherall Road Cheltenham
Location  = 86 G3

[ClaytonA]
Name      = Clayton RSL
Address   = 163 Carinish Road Clayton
Location  = 79 D2

[CueClubA]
Name      = Cue Club
Address   = 277 Brunswick Street Fitzroy
Location  = 2C B7

[DandClubA]
Name      = Dandenong Club
Address   = 1579 Heatherton Road Dandenong
Location  = 90 G3

[DandWSCA]
Name      = Dandenong WSC
Address   = 48 Wedge Street Dandenong
Location  = 90 G9

[FastEddiesA]
Name      = Fast Eddie's Snooker Room
Address   = 168 Chesterville Road Moorabbin
Location  = 77 J9

[KooyongA]
Name      = Kooyong Lawn Tennis Club
Address   = 489 Glenferrie Road Kooyong
Location  = 59 C3

[NBrightonA]
Name      = North Brighton Club
Address   = 14 Warleigh Grove Brighton
Location  = 67 G9

[PrahranA]
Name      = Prahran Club
Address   = 258 High Street Prahran
Location  = 58 E6

[PrincesA]
Name      = Prince's Snooker Centre
Address   = 1068 Dandenong Road Carnegie
Location  = 69 A3

[PrincesB]
Name      = Prince's Snooker Centre
Address   = 1068 Dandenong Road Carnegie
Location  = 69 A3

[PrincesC]
Name      = Prince's Snooker Centre
Address   = 1068 Dandenong Road Carnegie
Location  = 69 A3

[PrincesD]
Name      = Prince's Snooker Centre
Address   = 1068 Dandenong Road Carnegie
Location  = 69 A3

[PrincesM]
Name      = Prince's Snooker Centre
Address   = 1068 Dandenong Road Carnegie
Location  = 69 A3

[PrincesN]
Name      = Prince's Snooker Centre
Address   = 1068 Dandenong Road Carnegie
Location  = 69 A3

[RackEmUpA]
Name      = Rack-Em-Up
Address   = 28 John St Lilydale
Location  = 39 C4
URL       = 

[RACVA]
Name      = RACV Club
Address   = 123 Queen Street Melbourne
Location  = 1A H6

[RACVB]
Name      = RACV Club
Address   = 123 Queen Street Melbourne
Location  = 1A H6

[RedTriangA]
Name      = Red Triangle Snooker Room
Address   = 110a Argyle Street Fitzroy
Location  = 2C B7

[StMarysA]
Name      = St. Mary's Club
Address   = Bowen Street Camberwell
Location  = 59 K5
URL       = 

[SandyClubA]
Name      = Sandringham Club
Address   = 92 Beach Road Sandringham
Location  = 76 F8
URL       = 

[YarravilleA]
Name      = Yarraville Club
Address   = 135 Stephen Street Yarraville
Location  = 42 B9

[YarravilleB]
Name      = Yarraville Club
Address   = 135 Stephen Street Yarraville
Location  = 42 B9
*/

};

const Program = "FixtureMaker 4.1";
const C = 169; // Copyright symbol
const Copyright = "Copyright (c) 2000 Melbourne Software Company Pty Ltd. All Rights Reserved.";

const localTime = new Date();
let Second = localTime.getSeconds();
let Minute = localTime.getMinutes();
let Hour = localTime.getHours();
let Day = localTime.getDate();
let Month = localTime.getMonth() + 1; // Months are zero-based
let Year = localTime.getFullYear();

const Title = "Unknown Title";
const Season = `Season ${Year}`;
let NameFile;
let KeyFile;
let DateFile;
let Fixture;

// optional configuration file parameters
const TeamWidth = 21;
const HomeWidth = 32;
const AddressWidth = 48;
const LocationWidth = 7;
const GradeWidth = 20;
let OverRide;
const HomePage = "";
const ContactLabel = "Contact";
const GroundsLabel = "Grounds";
const NotesLabel = "Notes";
const TeamsLabel = "Teams";
const ClubsHtml = "Clubs.html";
const GroundsHtml = "Grounds.html";

let ConfigFile;
const LogFile = "FixtureMaker.log";
const Footer1 = `Copyright ${String.fromCharCode(C)} ${Year} ${Title}. All Rights Reserved.`;
const Footer2 = `Created on ${Day}/${Month}/${Year} at ${String(Hour).padStart(2, '0')}:${String(Minute).padStart(2, '0')} by ${Program}`;

const PreferredFont = 'verdana, helvetica, arial, sans serif';
const HeaderFontSize = "+2";

const Contact = {};
const Notes = {};

const Configuration = {};
const Names = {};
const Keys = {};
const Dates = {};
const OverRides = {};
const Byes = {};
const Clashes = {};
const TeamList = {};
let Section, Label, Value;
let Grade;

let Errors = 0;
let Warnings = 0;
let PageNumber = 0;
const PageDepth = 54;
let LineNumber = 0;
const PageRefs = [];

const ClubsList = "Clubs, Contacts and Grounds";
const GroundsList = "Grounds Directory";

function Error(Message) {
    Message = "Error:: " + Message + "\n";
    Errors++;
    console.log(Message);
    console.warn(Message);
}

function Fatal(Message) {
    Message = "Fatal:: " + Message + "\n";
    console.log(Message);
    throw new Error(Message);
}

function Info(Message) {
    Message = "Info:: " + Message + "\n";
    console.log(Message);
    console.warn(Message);
}

function Warning(Message) {
    Message = "Warning:: " + Message + "\n";
    Warnings++;
    console.log(Message);
    console.warn(Message);
}

function Debug(Message) {
    Message = "Debug:: " + Message + "\n";
    console.log(Message);
    console.warn(Message);
}

function NameOf(Item) {
    if (!Item) return "";
    if (Names[Item] && Names[Item]['Name']) {
        return Names[Item]['Name'];
    }
    Warning(`Name = undefined for ${Item}, using ${Item}`);
    return Item;
}

function OpenHtmlFile(FileName, Title) {
    FileName = `html/${FileName}`;
    if (!fs.existsSync("html")) {
        fs.mkdirSync("html", { recursive: true });
    }

    LineNumber = 0;
    const FIXTURE = fs.createWriteStream(FileName);
    FIXTURE.write(`<HTML>\n<TITLE>${Title}</TITLE>\n<BODY TEXT="#000000" BGCOLOR="#FFFFFF" LINK="#0000EE" VLINK="#551A8B" ALINK="#FF0000">\n<STYLE>\nH6 {page-break-before:always}\n</STYLE>\n`);
    return FIXTURE;
}

function CloseHtmlFile(FIXTURE) {
    FIXTURE.write(`</BODY>\n</HTML>\n`);
    FIXTURE.end();
}

function PageLinks(PageNumber, FIXTURE) {
    const Top = PageRefs[1];
    const Prev = PageRefs[PageNumber - 1];
    const Next = PageRefs[PageNumber + 1];

    if (HomePage) {
        FIXTURE.write(`<A HREF="${HomePage}"><FONT FACE="${PreferredFont}">Home</FONT></A>\n`);
    }
    if (PageNumber > 1 && Top) {
        FIXTURE.write(`<A HREF="${Top}"><FONT FACE="${PreferredFont}">Top</FONT></A>\n`);
    }
    if (Prev) {
        FIXTURE.write(`<A HREF="${Prev}"><FONT FACE="${PreferredFont}">Prev</FONT></A>\n`);
    }
    if (Next) {
        FIXTURE.write(`<A HREF="${Next}"><FONT FACE="${PreferredFont}">Next</FONT></A>\n`);
    }
}

//let PageNumber = 0;
//let LineNumber = 0;
//let PageDepth = 0;
let FIXTURE = '';
//let PreferredFont = 'Arial';
//let HeaderFontSize = 12;
//let Title = 'Title';
//let Season = 'Season';
//let Footer1 = 'Footer1';
//let Footer2 = 'Footer2';
//let PageRefs = [];
//let Configuration = {};
//let Names = {};
//let GroundsLabel = 'GroundsLabel';

function PageHeader(Ref, Heading) {
    PageNumber++;
    FIXTURE += `<A NAME="${Ref}"></A>`;
    PageLinks(PageNumber);
    PageRefs[PageNumber] = Ref;
    FIXTURE += `
<CENTER><PRE>
<FONT FACE="${PreferredFont}" SIZE="${HeaderFontSize}">${Title}</FONT>
<BR>
<FONT FACE="${PreferredFont}" SIZE="${HeaderFontSize}">${Season}</FONT>
<BR>
<FONT FACE="${PreferredFont}" SIZE="${HeaderFontSize}">${Heading}</FONT>
<BR>
</CENTER></PRE>`;
}

function PageFooter() {
    FIXTURE += `
<CENTER>
<I><FONT FACE="${PreferredFont}">${Footer1}</FONT></I>
<BR>
<I><FONT FACE="${PreferredFont}">${Footer2}</FONT></I>
<BR>
<BR>
</CENTER>`;
    PageLinks(PageNumber);
}

function PrintLine(Line) {
    LineNumber++;
    if (!Line) {
        Line = "<BR>";
        if (LineNumber > PageDepth) {
            Line = "<H6></H6>";
            LineNumber = -13; // TODO: fix hack
        }
    }
    FIXTURE += Line;
}

function NumAlpha(a, b) {
    const anum = a.match(/(^\\d+\\.?\\d*)/);
    const bnum = b.match(/(^\\d+\\.?\\d*)/);
    return (anum && bnum && anum[0] !== bnum[0])
        ? (Number(anum[0]) - Number(bnum[0])) : (a.localeCompare(b));
}

function ProcessConfiguration(ConfigFile, Config) {
    const fs = require('fs');
    let Section;

    try {
        const data = fs.readFileSync(ConfigFile, 'utf8');
        console.error(`Processing ${ConfigFile}`);
        Config[' File'] = { 'Name': ConfigFile };
        const lines = data.split('\n');
        for (let line of lines) {
            line = line.trim();
            // ignore comments (;) and blank lines
            if (/^\s*;/.test(line) || /^\s*$/.test(line)) continue;
            if (/^\s*\[\s*(.*)\s*\]\s*$/.test(line)) {
                Section = line.match(/^\s*\[\s*(.*)\s*\]\s*$/)[1];
                if (Config[Section]) {
                    throw new Error(`duplicate section ${Section} in ${ConfigFile}`);
                }
            } else if (/^\s*(\S+)\s*=\s*(.*)\s*$/.test(line)) {
                const [_, Label, Value] = line.match(/^\s*(\S+)\s*=\s*(.*)\s*$/);
                if (!Section) {
                    throw new Error(`${line} not in a section in ${ConfigFile}`);
                }
                if (Config[Section][Label]) {
                    console.warn(`duplicate label ${Label} in ${ConfigFile}`);
                }
                Config[Section][Label] = Value;
            } else {
                throw new Error(`unrecognized line in ${ConfigFile}: ${line}`);
            }
        }
    } catch (err) {
        console.error(err.message);
    }
}

function VerifyConfiguration(Config, Labels) {
    for (let Label of Labels) {
        for (let Section of Object.keys(Config[Label]).sort()) {
            if (!Config[Section]) {
                throw new Error(`${Section} defined in [${Label}] but [${Section}] is not defined`);
            }
        }
    }
}

function DumpConfiguration(Description, Config) {
    console.log(`Dumping ${Description}`);
    for (let Section of Object.keys(Config).sort()) {
        console.log(`[${Section}]`);
        for (let Label of Object.keys(Config[Section]).sort()) {
            const Value = Config[Section][Label];
            console.log(`${Label.padEnd(10)} = ${Value}`);
        }
        console.log('');
    }
}

function MakeGrade(Grade, GradeName, Dates, Key, Teams) {
    const Contact = Configuration[Grade]['Contact'];
    const Notes = Configuration[Grade]['Notes'];
    const TeamsArray = [];
    const TeamNames = [];
    const Homes = [];
    const Locations = [];
    let Team, Round;
    let i = 1;

    OpenHtmlFile(`${Grade}.html`, GradeName);

    Teams.split(" ").forEach(Team => {
        let TeamName = NameOf(Team);
        let Homes = Names[Team][GroundsLabel] || "";
        let TeamNames = "";

        if (!/^Bye/i.test(Team)) {
            if (!Homes) {
                Error(`${GroundsLabel} undefined for ${Team} in grade ${Grade}`);
                Homes = "TBA";
            }
        }

        // shift grounds off to the left !!
        const homeMatch = /^\s*(\S+)\s*(.*)$/.exec(Homes);
        if (homeMatch) {
            [Home, Names[Team][GroundsLabel]] = [homeMatch[1], homeMatch[2]];
        } else {
            [Home, Names[Team][GroundsLabel]] = ["", ""];
        }

        TeamNames = Names[Team][TeamsLabel] || "";
        // shift teams off to the left !!
        const teamNameMatch = /^\s*([^,]*),\s*(.*)$/.exec(TeamNames);
        if (teamNameMatch) {
            const ThisTeam = teamNameMatch[1].trim();
            Names[Team][TeamsLabel] = teamNameMatch[2];
            TeamName += ` ${ThisTeam}`;
            Names[Team]['Elevens'].push(ThisTeam);
        } else if (TeamNames) {
            const ThisTeam = TeamNames.trim();
            Names[Team][TeamsLabel] = "";
            TeamName += ` ${ThisTeam}`;
            Names[Team]['Elevens'].push(ThisTeam);
        }

        const Location = Names[Home]['Location'] || (Team.match(/^Bye/i) || Home === "TBA" ? "" : (() => {
            Warning(`Location undefined for ${Home}, using No Loc`);
            return "No Loc";
        })());

        const HomeName = NameOf(Home);

        console.log(`Team ${i.toString().padStart(2)} = ${TeamName}, ${HomeName}, ${Location}`);
        if (TeamName.length > TeamWidth) {
            Warning(`${TeamName} (${TeamName.length}) longer than ${TeamWidth} characters, truncated`);
        }
        if (HomeName.length > HomeWidth) {
            Warning(`${HomeName} (${HomeName.length}) longer than ${HomeWidth} characters, truncated`);
        }
        if (Location.length > LocationWidth) {
            Warning(`${Location} (${Location.length}) longer than ${LocationWidth} characters, truncated`);
        }
        TeamsArray[i] = Team;
        TeamNames[i] = TeamName;
        Homes[i] = Home;
        Locations[i] = Location;

        // TODO: check for duplicates
        TeamList[`${Team}${Grade}${TeamName}`] = {
            Team: Team,
            Grade: Grade,
            Home: [Home],
            Number: i
        };
        i++;
    });

    // Strip leading and trailing spaces from input
    TeamNames.forEach((name, index) => {
        if (name) {
            TeamNames[index] = name.trim();
        }
    });
    Homes.forEach((home, index) => {
        if (home) {
            Homes[index] = home.trim();
        }
    });
    Locations.forEach((location, index) => {
        if (location) {
            Locations[index] = location.trim();
        }
    });

    if (Dates[Dates] === undefined) {
        Error(`Section [${Dates}] not found in ${DateFile}`);
    }
    if (Keys[Key] === undefined) {
        Error(`Section [${Key}] not found in ${KeyFile}`);
    }

    PageHeader(`${Grade}.html`, GradeName);
}

console.log("<CENTER><PRE>");

if (Contact) {
    console.log(`<I><FONT FACE="${PreferredFont}">${Contact}</FONT></I>`);
}

if (Notes) {
    console.log(`<I>${Notes}</I>`);
}

if (Contact || Notes) {
    PrintLine("");
}

Object.keys(Keys[Key]).sort(NumAlpha).forEach(Round => {
    console.log(`Round ${Round.padStart(4)} Dates => ${Dates[Dates][Round]}`);
});

Object.keys(Keys[Key]).sort(NumAlpha).forEach(Round => {
    console.log(`Round ${Round.padStart(4)} Numbers => ${Keys[Key][Round]}`);
});

let HomeGames = [];
let AwayGames = [];
let MatchSequence = [];
let FirstTime = true;

Object.keys(Keys[Key]).sort(NumAlpha).forEach(Round => {
    let Numbers = Keys[Key][Round].replace(/\s*v\s*/ig, ' ').split(" ");
    let CheckNumbers = [];

    Numbers.forEach(num => {
        CheckNumbers[num] = (CheckNumbers[num] || 0) + 1;
    });

    for (let i = 1; i < Numbers.length; i++) {
        if (!(CheckNumbers[i] === undefined || CheckNumbers[i] === 1)) {
            Error(`Key ${Key} Round ${Round} Number ${i} appears ${CheckNumbers[i]} times`);
        }
    }

    if (!FirstTime) {
        PrintLine("");
    }
    FirstTime = false;
    PrintLine(`<B>Round ${Round} : ${Dates[Dates][Round]}</B>`);

    for (let i = 0; i < Numbers.length; i += 2) {
        let HomeNum = Numbers[i];
        let AwayNum = Numbers[i + 1];
        HomeGames[HomeNum] = (HomeGames[HomeNum] || 0) + 1;
        MatchSequence[HomeNum] = (MatchSequence[HomeNum] || '') + 'h';
        AwayGames[AwayNum] = (AwayGames[AwayNum] || 0) + 1;
        MatchSequence[AwayNum] = (MatchSequence[AwayNum] || '') + 'a';
        let Ground = Homes[HomeNum];
        let Location = Locations[HomeNum];
        let GroundName = NameOf(Ground);
        let BoldGround = 0;
        let UnderlineGround = 0;

        if (Names[Ground] && Names[Ground]['Font'] && /Bold/i.test(Names[Ground]['Font'])) {
            BoldGround = 1;
        }

        if (OverRides[Ground] && OverRides[Ground][Round]) {
            let Message = `moving ${Teams[HomeNum]} ${Grade} Rd ${Round} ${Ground} to `;
            Ground = OverRides[Ground][Round];
            Location = Names[Ground]['Location'];
            GroundName = NameOf(Ground);
            BoldGround = 1;
            UnderlineGround = 1;
            Message += Ground;

            if (!/^Bye/i.test(Teams[AwayNum])) {
                Info(Message);
                let ThisTeam = Teams[HomeNum];
                let TeamName = NameOf(Team); // TODO: this is wrong
                if (!TeamList[`${ThisTeam}${Grade}${TeamName}`]['Home'].includes(Ground)) {
                    TeamList[`${ThisTeam}${Grade}${TeamName}`]['Home'].push(Ground);
                }
                if (/TBA/i.test(Ground)) {
                    Warning(`No venue for ${ThisTeam} Round ${Round} ${Dates[Dates][Round]} ${Ground} ${Grade} vs ${Teams[AwayNum]}`);
                }
            }
        }

        if (/^Bye/i.test(Teams[AwayNum])) {
            const ByeKey = Round + Ground;
            const ByeDetails = `${Teams[HomeNum]} Round ${Round} ${Dates[Dates][Round]} ${Ground} ${Grade}`;
            Byes[ByeKey] = ByeDetails;
            // Info(`Bye: ${ByeDetails}`);
            Ground = "";
            GroundName = "";
        }

        const MatchDay = Configuration[Grade]['Day'] || "";
        const MatchKey = Ground + MatchDay + Math.floor(Round);
        const MatchDetails = `${Ground} ${Dates[Dates][Round]} ${TeamNames[HomeNum]} ${Grade}`;
        if (Clashes[MatchKey] !== undefined &&
            !/\.2$/.test(Round) && // TODO: fix
            !/^Bye/i.test(TeamNames[HomeNum]) &&
            !/^Bye/i.test(TeamNames[AwayNum]) &&
            !/TBA/i.test(Ground)) {
            Error(`${MatchDetails} clashes with\n        ${Clashes[MatchKey]}`);
        }
        if (!/^Bye/i.test(TeamNames[HomeNum]) && !/^Bye/i.test(TeamNames[AwayNum])) {
            Clashes[MatchKey] = MatchDetails;
        }

        Location = Names[Ground]['Location'] || "";
        Spacing = HomeWidth - GroundName.length;
        PrintLine(sprintf(
            `%${-TeamWidth}.${TeamWidth}s v %${-TeamWidth}.${TeamWidth}s @ %s%s%s%s%s %${-Spacing}.${Spacing}s %${-LocationWidth}.${LocationWidth}s`,
            TeamNames[HomeNum],
            TeamNames[AwayNum],
            BoldGround ? "<B>" : "",
            UnderlineGround ? "<U>" : "",
            GroundName,
            BoldGround ? "</B>" : "",
            UnderlineGround ? "</U>" : "",
            "", Location
        ));
    }

    console.log(`</PRE></CENTER>`);
    PageFooter();

    for (let i = 1; i <= HomeGames.length - 1; i++) {
        console.log(`Key ${Key} => Team ${i} has ${HomeGames[i]} home games and ${AwayGames[i]} away games ${MatchSequence[i]}`);
    }
    CloseHtmlFile();
});

// main program

process.stderr.write(`${Program}\n`);
process.stderr.write(`${Copyright}\n`);

if (process.argv.length < 3) {
    Fatal(`usage ${process.argv[0]} configuration-file [logfile]`);
}

ConfigFile = process.argv[2];
LogFile = process.argv[3];
const fs = require('fs');

if (LogFile) {
    const logStream = fs.createWriteStream(LogFile, { flags: 'a' });
    process.stdout.write = logStream.write.bind(logStream);
    process.stderr.write = logStream.write.bind(logStream);
}

process.stderr.write(`Review ${LogFile} for errors\n`);

// process configuration file given on command line
ProcessConfiguration(ConfigFile, Configuration);
VerifyConfiguration(Configuration, 'GRADES');

// mandatory parameters
Title = Configuration['Mandatory']['Title'] || Fatal(`[Mandatory] Title = not specified in ${ConfigFile}`);
Season = Configuration['Mandatory']['Season'] || Fatal(`[Mandatory] Season = not specified in ${ConfigFile}`);
NameFile = Configuration['Mandatory']['NameFile'] || Fatal(`[Mandatory] NameFile = not specified in ${ConfigFile}`);
KeyFile = Configuration['Mandatory']['KeyFile'] || Fatal(`[Mandatory] KeyFile = not specified in ${ConfigFile}`);
DateFile = Configuration['Mandatory']['DateFile'] || Fatal(`[Mandatory] DateFile = not specified in ${ConfigFile}`);
Fixture = Configuration['Mandatory']['Fixture'] || Fatal(`[Mandatory] Fixture = not specified in ${ConfigFile}`);

// optional parameters
TeamWidth = Configuration['Optional']['TeamWidth'] || TeamWidth;
HomeWidth = Configuration['Optional']['HomeWidth'] || HomeWidth;
AddressWidth = Configuration['Optional']['AddressWidth'] || AddressWidth;
LocationWidth = Configuration['Optional']['LocationWidth'] || LocationWidth;
GradeWidth = Configuration['Optional']['GradeWidth'] || GradeWidth;
OverRide = Configuration['Optional']['OverRide'] || OverRide;
HomePage = Configuration['Optional']['HomePage'] || HomePage;
PageDepth = Configuration['Optional']['PageDepth'] || PageDepth;
ContactLabel = Configuration['Optional']['Contact'] || ContactLabel;
GroundsLabel = Configuration['Optional']['Grounds'] || GroundsLabel;
NotesLabel = Configuration['Optional']['Notes'] || NotesLabel;
TeamsLabel = Configuration['Optional']['Teams'] || TeamsLabel;
ClubsHtml = Configuration['Optional']['ClubsHtml'] || ClubsHtml;
GroundsHtml = Configuration['Optional']['GroundsHtml'] || GroundsHtml;

Footer1 = `Copyright ${C} ${Year} ${Title} ${HomePage}`;

// process mandatory configuration files
ProcessConfiguration(NameFile, Names);
VerifyConfiguration(Names, 'CLUBS', 'GROUNDS');

// set up Bye & TBA
Names['CLUBS']['Bye'] = "";
Names['Bye']['Name'] = 'Bye';
Names['Bye'][GroundsLabel] = "";
Names['TBA']['Name'] = 'TBA';

ProcessConfiguration(KeyFile, Keys);
ProcessConfiguration(DateFile, Dates);

// process optional configuration files
if (OverRide) {
    ProcessConfiguration(OverRide, OverRides);
}

// open output file
OpenHtmlFile(Fixture, Title);

PageHeader(Fixture, "Table of Contents");
process.stdout.write(`<CENTER>\n`);

let PageNum = 2;
for (const Grade of Object.keys(Configuration['GRADES']).sort(NumAlpha)) {
    if (Configuration[Grade] === undefined) {
        Error(`Grade ${Grade} undefined`);
        continue;
    }
    const GradeName = Configuration[Grade]['Name'];
    process.stdout.write(`<A HREF="${Grade}.html"><FONT FACE="${PreferredFont}">${GradeName}</FONT></A><BR>\n`);
    PageRefs[PageNum++] = `${Grade}.html`;
}

//let PageRefs = [];
PageRefs[PageNum++] = ClubsHtml;
PageRefs[PageNum++] = GroundsHtml;
PageFooter();
CloseHtmlFile();

for (let Grade of Object.keys(Configuration['GRADES']).sort(NumAlpha)) {
    if (Configuration[Grade] === undefined) continue;
    let GradeName = Configuration[Grade]['Name'];
    let Dates = Configuration[Grade]['Dates'];
    let Day = Configuration[Grade]['Day'] || "Saturday";
    let Key = Configuration[Grade]['Key'];
    let Contact = Configuration[Grade]['Contact'];
    let Teams = Configuration['ALLOCATION'][Grade];
    let TeamsOK = true;

    console.log(`[${Grade}]`);
    console.log(`Name    = ${GradeName}`);
    console.log(`Dates   = ${Dates}`);
    console.log(`Day     = ${Day}`);
    console.log(`Key     = ${Key}`);
    if (Contact) console.log(`Contact = ${Contact}`);
    console.log(`Teams   = ${Teams}`);

    for (let Team of Teams.split(" ")) {
        if (Names[Team] === undefined || Names[Team]['Name'] === undefined) {
            Error(`[${Team}] undefined in grade ${Grade}`);
            TeamsOK = false;
        }
    }
    if (TeamsOK) MakeGrade(Grade, GradeName, Dates, Key, Teams);
    console.log("\n");
}

OpenHtmlFile(ClubsHtml, ClubsList);

PageHeader(ClubsHtml, ClubsList);
console.log(`<CENTER><PRE>`);
console.log(`[${ClubsList}]`);

let LastTeam = 0, LastGrade = 0, LastHome = 0, LastNumber = 0, LastDay = 0, LastReversed = 0;
let Elevens = [
    "1st XI", "2nd XI", "3rd XI", "4th XI",
    "5th XI", "6th XI", "7th XI", "8th XI",
    "9th XI", "10th XI", "11th XI", "12th XI",
    "13th XI", "14th XI", "15th XI", "16th XI",
];
let Eleven = 0;

for (let Key of Object.keys(TeamList).sort()) {
    let Team = TeamList[Key]['Team'];
    let Grade = TeamList[Key]['Grade'];
    let Day = Configuration[Grade]['Day'] || "Saturday";
    let Home = TeamList[Key]['Home'][0] || "TBA";
    let Location = Names[Home]['Location'] || "";
    let Number = TeamList[Key]['Number'];
    let GradeName = Configuration[Grade]['Name'];
    let GradeKey = Configuration[Grade]['Key'];
    let Reversed = /reverse/i.test(GradeKey);
    let HomeName = NameOf(Home);

    if (LastTeam && Team !== LastTeam) console.log("\n");
    console.log(`${Number.toString().padStart(2)} ${Team.padEnd(TeamWidth)} ${Grade.padEnd(GradeWidth)} ${Home.padEnd(HomeWidth)} ${Location.padEnd(LocationWidth)}`);

    if (!/^Bye/i.test(Team)) {
        if (Team !== LastTeam) {
            let TeamName = Names[Team]['Name'] || "";
            let FullName = Names[Team]['FullName'] || NameOf(Team);
            let Contact = Names[Team][ContactLabel] || "";
            let Telephone = Names[Team]['Telephone'] || "";
            let Notes = Names[Team][NotesLabel] || "";
            Eleven = 0;
            if (LastTeam) PrintLine("");
            PrintLine(`<B>${FullName} (${TeamName})</B>`);
            PrintLine(Contact);
            if (Telephone) PrintLine(`Clubrooms: ${Telephone}`);
            if (Notes) PrintLine(`<I>${Notes}</I>`);
        }
        let ThisEleven = Elevens[Eleven];
        if (Names[Team]['Elevens'] !== undefined) {
            ThisEleven = Names[Team]['Elevens'][Eleven];
        }
        if (ThisEleven === undefined) Error("ThisEleven undefined");
        PrintLine(`${ThisEleven.padEnd(7)} ${GradeName.padEnd(GradeWidth)} ${HomeName.padEnd(HomeWidth)} ${Location.padEnd(LocationWidth)}`);
        Eleven++;
        for (let i = 1; Names[TeamList[Key]['Home']][i] !== undefined; i++) {
            let AltHome = TeamList[Key]['Home'][i];
            // Additional logic for AltHome can be added here
        }
    }
}

for (let i = 1; TeamList[Key].Home[i] !== undefined; i++) {
    let AltHome = TeamList[Key].Home[i];
    let AltLocation = Names[AltHome].Location || "";
    let AltHomeName = NameOf(AltHome);
    PrintLine(sprintf(
        "%-7.7s " +
        "%-${GradeWidth}.${GradeWidth}s  " +
        "%-${HomeWidth}.${HomeWidth}s " +
        "%-${LocationWidth}.${LocationWidth}s",
        "", "", AltHomeName, AltLocation));
}

if (Team === LastTeam && Home === LastHome && Day === LastDay) {
    if (Math.floor((Number + 1) / 2) !== Math.floor((LastNumber + 1) / 2) ||
        ((Reversed ^ LastReversed) ? Number !== LastNumber : Number === LastNumber)) {
        // TODO: fix reverse draw
        let Message =
            sprintf("%s %s %s has key %d but\n",
                LastTeam, LastHome, LastGrade, LastNumber) +
            sprintf("        %s %s %s has key %d, home games will clash",
                Team, Home, Grade, Number);
        Error(Message);
    }
}
[LastTeam, LastGrade, LastHome, LastNumber, LastDay, LastReversed] =
    [Team, Grade, Home, Number, Day, Reversed];

print(FIXTURE + "</PRE></CENTER>");

PageFooter();
CloseHtmlFile();

OpenHtmlFile(GroundsHtml, GroundsList);

PageHeader(GroundsHtml, GroundsList);
print(FIXTURE + "<CENTER><PRE>");

for (let Key of Object.keys(Names.GROUNDS).sort((a, b) => Names[a].Name.localeCompare(Names[b].Name))) {
    let Name = Names[Key].Name || "";
    let Address = Names[Key].Address || "";
    let Location = Names[Key].Location || "";
    PrintLine(sprintf(
        "%-${HomeWidth}.${HomeWidth}s  " +
        "%-${AddressWidth}.${AddressWidth}s  " +
        "%-${LocationWidth}.${LocationWidth}s",
        Name, Address, Location));
}

print(FIXTURE + "</PRE></CENTER>");

PageFooter();
CloseHtmlFile();

console.log("\n");
console.log("[List of Byes]");
for (let Key of Object.keys(Byes).sort(NumAlpha)) {
    console.log("Bye: " + Byes[Key]);
}

Info(`${Errors} errors and ${Warnings} warnings, see ${LogFile} for details`);


</script>
