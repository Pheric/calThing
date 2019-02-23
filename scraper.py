import sys

class Event:
    def __init__(self, name):
        self.name = name
        self.desc = ''
        self.when = ''
        self.where = ''
        self.contact = ''

    def file_format(self):
        return '{}\n\t{}\n\t{}\n\t{}\n\t{}\n'.format(self.name, self.desc, self.when, self.where, self.contact)

f = open(sys.argv[1], 'r')
ev = 0
while True:
    line = f.readline()
    if line == 'Events\n':
        ev += 1
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
for line in lines:
    gi = line.split(') ')
    if len(gi) == 2 and gi[1] == 'General Information':
        newlines.append(gi[0] + ')')
        break
        newlines.append(gi[1])
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
line_counter = 0
section = 0
#return lines
events.append(Event(lines[0]))

for i in range(1,len(lines)):
    if section == 0:
        #if lines[i] == 'General Information':
        #    section += 1
        #    continue
        if line_counter == 0:
            events[-1].desc = lines[i]
            line_counter += 1
        elif line_counter == 1:
            events[-1].when = lines[i]
            line_counter += 1
        elif line_counter == 2:
            events[-1].where = lines[i]
            line_counter += 1
        else:
            line = lines[i].split(') ')
            events[-1].contact = line[0] + ')'
            line_counter = 0
            if len(line) > 1:
                if line[1] == 'General Information':
                    section += 1
                    continue
                #print(line[1] + '\n')
                events.append(Event(line[1]))
    elif section == 1:
        pass
    elif section == 2:
        pass

with open(sys.argv[2], 'w') as o:
    for e in events:
        o.write(e.file_format())
