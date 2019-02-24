import sys
import re

class Event:
    def __init__(self, name):
        self.name = name
        self.desc = ''
        self.when = ''
        self.where = ''
        self.contact = ''

    def file_format(self):
        return '{}\n{}\n{}\n{}\n{}\n'.format(self.name, self.desc, self.when, self.where, self.contact)

# class GeneralInfo:
    # def __init__(self, name):
        # self.name = name
        # self.desc = ''

    # def file_format(self):
        # return 'NAME:{}\nDESC:{}\n'.format(self.name, self.desc)

def format_string(str):
    return re.sub('([a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+)', '<a href="mailto:\g<0>">\g<0></a>', re.sub('=[0-9A-Z]{2}', '', str.replace('\n','<br>').replace('=99', '\'')))

f = open(sys.argv[1], 'r')
ev = 0
ginfos = []
doing_geninfo = False
while True:
    line = f.readline()
    if line == 'Events\n':
        ev += 1
    if line == 'General Information\n':
        doing_geninfo = True
    if line == 'Employment Opportunities\n':
        doing_geninfo = False
    if doing_geninfo:
        ginfos.append(line[:-1])
    if ev == 2:
        break

lines = []
templine = ''
while True:
    line = f.readline()[:-1]

    if line == 'Content-type: text/html;':
        counter = 0
        for i in range(len(lines)):
            if lines[len(lines)-2-i] == 'Contact:':
                break
            else:
                counter += 1
        lines = lines[:-counter]
        lines[-1] = ' '.join(lines[-1].split(' ')[0:2])
        break

    if line != '':
        if line[-1] == '=':
            templine += line[:-1]
        else:
            if line[-3:] == '=20':
                line = line[:-3]
            lines.append(templine + line)
            templine = ''

newlines = []
templine = ''
name = True
checking_gen_info = False
for line in lines:
    # print('CHECKING: {}\n\nname = {}\n'.format(line, name))
    gi = line.split(') ')
    if len(gi) == 2 and gi[1] == 'General Information':
        newlines.append(gi[0] + ')')
        newlines.append(gi[1])
        checking_gen_info = True
        continue
    if checking_gen_info == True:
        if len(line) > len('Employment Opportunities'):
            if line[-len('Employment Opportunities'):] == 'Employment Opportunities':
                checking_gen_info = False
                newlines.append(templine + line[:-len('Employment Opportunities')])
                newlines.append('Employment Opportunities')
                continue
        for i in ginfos:
            if len(line) > len(i):
                none = True
                if line[-len(i):] == i:
                    # print('found {}'.format(i))
                    newlines.append(templine + line[:-len(i)])
                    newlines.append(line[-len(i):])
                    templine = ''
                    none = False
                if none:
                    templine += '<br>' + line
                    # print("\nadding {}\n\n{}".format(line[:-len(i)], line[-len(i):]))
    if name:
        newlines.append(line)
        name = False
    elif line in ['When:','Where:','Contact:']:
        newlines.append(templine)
        templine = ''
        if line == 'Contact:':
            name = True
    else:
        if templine == '':
            templine += line
        else:
            templine += '\n' + line
lines = newlines

events = []
infos = []
line_counter = 0
section = 0
events.append(Event(lines[0]))

for i in range(1,len(lines)):
    # print(lines[i])
    if section == 0:
        if lines[i] == 'General Information':
            section += 1
            line_counter = 0
            continue
        if line_counter == 0:
            events[-1].desc = format_string(lines[i])
            line_counter += 1
        elif line_counter == 1:
            events[-1].when = format_string(lines[i])
            line_counter += 1
        elif line_counter == 2:
            events[-1].where = format_string(lines[i])
            line_counter += 1
        else:
            line = lines[i].split(') ')
            events[-1].contact = format_string(line[0] + ')')
            line_counter = 0
            if len(line) > 1:
                events.append(Event(format_string(line[1])))
    elif section == 1:
        pass
        # eo = lines[i].split(' ')
        # if lines[i] == 'Employment Opportunities':
            # section += 1
            # continue
        # if line_counter == 0:
            # infos.append(GeneralInfo(format_string(lines[i])))
            # line_counter += 1
        # else:
            # infos[-1].desc = format_string(lines[i])
            # line_counter = 0
    elif section == 2:
        pass

with open(sys.argv[2], 'w') as o:
    o.write(str(len(events))+'\n')
    for e in events:
        o.write(e.file_format())
    # for g in infos:
        # o.write(g.file_format())
